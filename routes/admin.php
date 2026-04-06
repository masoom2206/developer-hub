<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\SponsorController;
use App\Http\Controllers\Admin\SubscriberController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::delete('/comments/{comment}', [DashboardController::class, 'deleteComment'])->name('comments.destroy');

    // Posts
    Route::resource('posts', PostController::class)->except(['show']);

    // Sponsors
    Route::get('/sponsors', [SponsorController::class, 'index'])->name('sponsors.index');
    Route::get('/sponsors/create', [SponsorController::class, 'create'])->name('sponsors.create');
    Route::post('/sponsors', [SponsorController::class, 'store'])->name('sponsors.store');
    Route::delete('/sponsors/{sponsor}', [SponsorController::class, 'destroy'])->name('sponsors.destroy');

    // Subscribers
    Route::get('/subscribers', [SubscriberController::class, 'index'])->name('subscribers.index');
    Route::get('/subscribers/export', [SubscriberController::class, 'export'])->name('subscribers.export');
    Route::delete('/subscribers/{subscriber}', [SubscriberController::class, 'destroy'])->name('subscribers.destroy');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.updateRole');
});
