<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/vue/{any?}', function () {
    return view('vue');
})->where('any', '.*');


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