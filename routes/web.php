<?php

use Illuminate\Support\Facades\Route;

// Redirect root to dashboard or login
Route::get('/', function () {
    return redirect('/login');
});

require __DIR__.'/auth.php';
