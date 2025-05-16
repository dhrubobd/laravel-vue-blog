<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{postID}/show', [PostController::class, 'show'])->name('posts.show');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);


Route::middleware('auth')->group(function () {
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts/create', [PostController::class, 'store'])->name('posts.store');

    //Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update-picture', [ProfileController::class, 'updateProfilePicture']);
    Route::post('/profile/update-username', [ProfileController::class, 'updateUsername']);
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword']);

    Route::get('/tags', [PostController::class, 'getTags']); // For fetching tags

    Route::post('/posts/{post_id}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
    Route::delete('/posts/{post}/comments/{comment}', [CommentController::class, 'destroy']);

    Route::post('/posts/{post_id}/like', [LikeController::class, 'toggle']);

    Route::get('/bookmarks', [BookmarkController::class, 'index']);
    Route::post('/posts/{post}/bookmark', [BookmarkController::class, 'toggle']);
    Route::delete('/bookmarks/{bookmark}', [BookmarkController::class, 'destroy']);
});
