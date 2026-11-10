<?php

use App\Http\Controllers\StudentAuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::redirect('/vue', '/home');

Route::get('/login', [StudentAuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [StudentAuthController::class, 'login'])
    ->middleware('throttle:student-login')
    ->name('student.login');

Route::post('/logout', [StudentAuthController::class, 'logout'])
    ->name('logout');

Route::get('/admin/login', function () {
    return view('vue');
});

Route::get('/admin/dashboard', function () {
    return view('vue');
});

Route::get('/admin/add-book', function () {
    return view('vue');
});

Route::get('/admin/borrow-approval', function () {
    return view('vue');
});

Route::get('/admin/return-book', function () {
    return view('vue');
});

Route::get('/admin/borrowers', function () {
    return view('vue');
});

Route::get('/admin/{any?}', function () {
    return view('vue');
})->where('any', '.*');

Route::middleware('auth')->group(function () {
    Route::get('/student/session', [StudentAuthController::class, 'session'])
        ->name('student.session');

    Route::get('/home/{any?}', function () {
        return view('vue');
    })->where('any', '.*')->name('student.home');

    Route::get('/book/{id}', function () {
        return view('vue');
    });

    Route::get('/my-library', function () {
        return view('vue');
    });

    Route::get('/bookmarks', function () {
        return view('vue');
    });

    Route::get('/scan', function () {
        return view('vue');
    });

    Route::get('/account', function () {
        return view('vue');
    });
});
