<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    // return view('welcome');
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return redirect()->route('dashboard');
})->middleware(['auth']);

require __DIR__.'/auth.php';

