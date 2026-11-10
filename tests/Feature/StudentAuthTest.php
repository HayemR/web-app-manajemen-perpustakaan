<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_public_and_admin_routes_remain_unchanged(): void
    {
        $this->get('/login')->assertOk()->assertViewIs('vue');
        $this->get('/admin/login')->assertOk();
        $this->get('/admin/dashboard')->assertOk();
    }

    public function test_guests_are_redirected_from_every_student_page_to_login(): void
    {
        $studentPages = [
            '/home',
            '/book/42',
            '/my-library',
            '/bookmarks',
            '/scan',
            '/account',
        ];

        foreach ($studentPages as $studentPage) {
            $this->get($studentPage)->assertRedirect(route('login'));
        }
    }

    public function test_authenticated_student_can_view_their_session_details(): void
    {
        $student = User::factory()->create([
            'name' => 'Ayu Pratama',
            'nis' => '2024-01234',
            'email' => 'ayu@example.test',
        ]);

        $this->actingAs($student)
            ->getJson('/student/session')
            ->assertOk()
            ->assertJsonPath('data.name', 'Ayu Pratama')
            ->assertJsonPath('data.nis', '2024-01234')
            ->assertJsonPath('data.email', 'ayu@example.test');
    }

    public function test_guest_cannot_view_student_session_details(): void
    {
        $this->getJson('/student/session')->assertUnauthorized();
    }

    public function test_student_can_sign_in_with_nis_and_password(): void
    {
        $student = User::factory()->create([
            'nis' => '2024-01234',
            'password' => 'correct-password',
        ]);

        $this->postJson('/login', [
            'nis' => '2024-01234',
            'password' => 'correct-password',
        ])
            ->assertOk()
            ->assertJsonPath('redirect', '/home');

        $this->assertAuthenticatedAs($student, 'web');
    }

    public function test_invalid_student_credentials_are_rejected(): void
    {
        User::factory()->create([
            'nis' => '2024-01234',
            'password' => 'correct-password',
        ]);

        $this->postJson('/login', [
            'nis' => '2024-01234',
            'password' => 'incorrect-password',
        ])
            ->assertStatus(422)
            ->assertJsonPath('errors.nis.0', 'Nomor induk siswa atau kata sandi tidak sesuai.');

        $this->assertGuest('web');
    }

    public function test_login_requires_both_student_id_and_password(): void
    {
        $this->postJson('/login', [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['nis', 'password']);
    }

    public function test_login_attempts_are_rate_limited_per_student_id(): void
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->postJson('/login', [
                'nis' => '2024-01234',
                'password' => 'incorrect-password',
            ])->assertStatus(422);
        }

        $this->postJson('/login', [
            'nis' => '2024-05678',
            'password' => 'incorrect-password',
        ])->assertStatus(422);

        $this->postJson('/login', [
            'nis' => '2024-01234',
            'password' => 'incorrect-password',
        ])->assertStatus(429);
    }

    public function test_student_can_log_out_and_invalidate_the_session(): void
    {
        $student = User::factory()->create(['nis' => '2024-01234']);

        $this->actingAs($student)
            ->postJson('/logout')
            ->assertNoContent();

        $this->assertGuest('web');
    }

    public function test_guest_can_log_out_and_return_a_successful_response(): void
    {
        $this->postJson('/logout')->assertNoContent();

        $this->assertGuest('web');
    }

    public function test_existing_students_can_keep_nis_blank_until_it_is_assigned(): void
    {
        $existingStudent = User::factory()->create(['nis' => null]);
        $anotherExistingStudent = User::factory()->create(['nis' => null]);
        $assignedStudent = User::factory()->create(['nis' => '2024-05678']);

        $this->assertNull($existingStudent->nis);
        $this->assertNull($anotherExistingStudent->nis);
        $this->assertSame('2024-05678', $assignedStudent->nis);
    }

    public function test_assigned_student_ids_must_be_unique(): void
    {
        User::factory()->create(['nis' => '2024-05678']);

        $this->expectException(QueryException::class);

        User::factory()->create(['nis' => '2024-05678']);
    }
}
