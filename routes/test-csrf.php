<?php

use Illuminate\Support\Facades\Route;

// Route to display a test form
Route::get('/test-csrf-form', function () {
    return view('test-csrf-form');
});

// Route to handle the form submission
Route::post('/test-csrf', function () {
    return response()->json([
        'message' => 'CSRF token is valid!',
        'data' => request()->all(),
    ]);
})->middleware('web');

// Route to get current CSRF token
Route::get('/csrf-token', function () {
    return response()->json([
        'csrf_token' => csrf_token(),
    ]);
});
