<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/lifecycle-test', function () {
    return response() -> json([
        'PHP version' => PHP_VERSION,
        'time' => now()->toIso8601String(),
    ]);
});
