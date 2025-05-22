<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');


Route::get('/create', function () {
    return view('create');
})->name('create');


Route::post('/store', function (Request $request) {

    $validated = $request->validate([
        'title' => 'required|min:3|max:255',
        'post' => 'required',
    ]);

    $title = $request->input('title');
    $post = $request->input('post');
    // Handle the form submission and store the data
    // For example, you can use a model to save the data to the database
    return redirect()->route('display')->with($validated);
})->name('store');


Route::get('/display', function () {
    return view('display', [
        'title' => session('title'),
        'post' => session('post')
    ]);
})->name('display');
