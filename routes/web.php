<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/vue', function () {
    return view('vue');
});

Route::get('/admin/login', function () {
    return view('vue');
});