<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    UserController,
    PostController,
    CommentController,
    LikeController,
    AdminController,
    TagController
};
use App\Http\Middleware\AdminOnly;
use App\Http\Middleware\SuperOnly;

// Home
Route::view('/', 'home')->name('home');

// Authentication
Route::controller(UserController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');

    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register')->name('register.submit');
});

// Public Post Routes
Route::controller(PostController::class)->prefix('posts')->name('posts.')->group(function () {
    Route::get('/', 'index')->name('display');
    Route::get('/create', 'create')->name('create')->middleware(('auth')); // Must be before 'show'!
    Route::get('/{post}', 'show')->name('show');
});

// Authenticated User Routes
Route::middleware('auth')->group(function () {

    // Posts
    Route::controller(PostController::class)->prefix('posts')->name('posts.')->group(function () {
        Route::post('/', 'store')->name('store');
        Route::get('/{post}/edit', 'edit')->name('edit');
        Route::put('/{post}', 'update')->name('update');
        Route::delete('/{post}', 'destroy')->name('destroy');

        Route::post('/{post}/like', [LikeController::class, 'toggle'])->name('like');
        Route::post('/{post}/view', 'logView')->name('logView');
    });

    // User Profile
    Route::prefix('user')->name('user.')->controller(UserController::class)->group(function () {
        Route::get('/', 'show')->name('show');
        Route::get('/posts', 'userPosts')->name('posts');
        Route::get('/commented', 'commentedPosts')->name('commented');
        Route::get('/liked', 'likedPosts')->name('liked');
        Route::get('/viewed', 'viewedPosts')->name('viewed');

        Route::get('/edit/{user}', 'edit')->name('edit');
        Route::put('/{user}', 'update')->name('update');
        Route::delete('/{user}', 'destroy')->name('destroy');
    });

    // Comments
    Route::controller(CommentController::class)->group(function () {
        Route::post('/posts/{post}/comments', 'store')->name('comments.store');
        Route::get('/user/{user}/comments', 'index')->name('comments.index');
        Route::delete('/posts/comments/{comment}', 'destroy')->name('comments.destroy');
    });
});

// Admin Only
Route::middleware(['auth', AdminOnly::class])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // Tags
    Route::controller(TagController::class)->prefix('tags')->name('tags.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::put('/', 'update')->name('update');
        Route::delete('/{tag}', 'destroy')->name('destroy');
    });

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
});

// Super Admin Only
Route::middleware(['auth', SuperOnly::class])->prefix('super')->name('super.')->group(function () {
    Route::get('/register', [AdminController::class, 'showRegistrationForm'])->name('register');
});
