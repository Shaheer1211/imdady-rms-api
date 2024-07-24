<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->to('https://imdady.com');
});

// This should be the last route to catch any unmatched routes
Route::fallback(function () {
    return redirect()->to('https://imdady.com');
});