<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/vue/{any?}', function () {
    return view('vue');
})->where('any', '.*');

Route::get('/admin/{any?}', function () {
    return view('vue');
})->where('any', '.*');

Route::get('/my-library', function () {
    return view('vue');
});