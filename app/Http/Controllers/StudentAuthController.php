<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class StudentAuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('student.home');
        }

        return view('vue');
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'nis' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'max:255'],
            'remember' => ['sometimes', 'boolean'],
        ], [
            'nis.required' => 'Nomor induk siswa wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        if (! Auth::guard('web')->attempt([
            'nis' => $credentials['nis'],
            'password' => $credentials['password'],
        ], $request->boolean('remember'))) {
            return response()->json([
                'message' => 'Nomor induk siswa atau kata sandi tidak sesuai.',
                'errors' => [
                    'nis' => ['Nomor induk siswa atau kata sandi tidak sesuai.'],
                ],
            ], 422);
        }

        $request->session()->regenerate();

        return response()->json([
            'redirect' => route('student.home', absolute: false),
        ]);
    }

    public function session(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user instanceof User) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return response()->json([
            'data' => $user->only(['name', 'nis', 'email']),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([], 204);
    }
}
