<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_books(): void
    {
        $category = Category::factory()->create();
        Book::factory()->count(3)->create(['category_id' => $category->id]);

        $response = $this->getJson('/api/books');

        $response->assertStatus(200)
            ->assertJsonStructure(['status', 'data']);
    }

    public function test_can_create_book_with_valid_data(): void
    {
        $category = Category::factory()->create();

        $data = [
            'category_id' => $category->id,
            'isbn_barcode' => '9780132350884',
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'publisher' => 'Prentice Hall',
            'published_year' => '2008',
            'stock' => 5,
            'cover_image_url' => 'https://example.com/cover.jpg',
            'description' => 'Great software craftsmanship book',
        ];

        $response = $this->postJson('/api/books', $data);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'Clean Code')
            ->assertJsonPath('data.stock', 5);

        $this->assertDatabaseHas('books', ['title' => 'Clean Code']);
    }

    public function test_can_upload_cover_image_file(): void
    {
        Storage::fake('public');
        $category = Category::factory()->create();

        $file = UploadedFile::fake()->image('cover.jpg', 300, 400);

        $response = $this->postJson('/api/books', [
            'category_id' => $category->id,
            'isbn_barcode' => '9780132350885',
            'title' => 'Clean Architecture',
            'author' => 'Robert C. Martin',
            'publisher' => 'Prentice Hall',
            'published_year' => '2017',
            'stock' => 3,
            'cover_image' => $file,
        ]);

        $response->assertStatus(201);
        $book = Book::where('isbn_barcode', '9780132350885')->first();
        $this->assertNotNull($book->cover_image_url);
    }

    public function test_can_lookup_isbn_from_external_api(): void
    {
        Http::fake([
            'https://www.googleapis.com/books/v1/volumes*' => Http::response([
                'items' => [
                    [
                        'volumeInfo' => [
                            'title' => 'Refactoring',
                            'authors' => ['Martin Fowler'],
                            'publisher' => 'Addison-Wesley',
                            'publishedDate' => '2018-11-20',
                            'description' => 'Improving the Design of Existing Code',
                            'imageLinks' => [
                                'thumbnail' => 'http://books.google.com/thumbnail.jpg',
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->getJson('/api/books/lookup-isbn?isbn=9780134757599');

        $response->assertStatus(200)
            ->assertJsonPath('data.title', 'Refactoring')
            ->assertJsonPath('data.author', 'Martin Fowler')
            ->assertJsonPath('data.published_year', '2018')
            ->assertJsonPath('data.cover_image_url', 'https://books.google.com/thumbnail.jpg');
    }

    public function test_cannot_delete_book_with_active_loans(): void
    {
        $book = Book::factory()->create();
        $user = User::factory()->create();

        Transaction::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => Transaction::STATUS_ACTIVE,
        ]);

        $response = $this->deleteJson("/api/books/{$book->id}");

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'message' => 'Buku tidak dapat dihapus karena masih sedang dipinjam atau menunggu persetujuan.',
            ]);

        $this->assertNotSoftDeleted('books', ['id' => $book->id]);
    }
}
