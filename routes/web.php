<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\SnippetController;
use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Search
Route::get('/search', [SearchController::class, 'index'])->name('search');

// SEO
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// Blog
Route::get('/blog', [PostController::class, 'index'])->name('posts.index');
Route::get('/blog/{post:slug}', [PostController::class, 'show'])->name('posts.show')->middleware('track.views');
Route::post('/blog/{post}/comments', [CommentController::class, 'store'])->name('comments.store')->middleware('auth');

// Tools
Route::get('/tools', [ToolController::class, 'index'])->name('tools.index');
Route::get('/tools/{tool:slug}', [ToolController::class, 'show'])->name('tools.show');
Route::post('/tools/markdown-render', [ToolController::class, 'markdownRender'])->name('tools.markdown.render');

// Snippets
Route::get('/snippets', [SnippetController::class, 'index'])->name('snippets.index');
Route::middleware('auth')->group(function () {
    Route::get('/snippets/create', [SnippetController::class, 'create'])->name('snippets.create');
    Route::post('/snippets', [SnippetController::class, 'store'])->name('snippets.store');
    Route::get('/snippets/{snippet}/edit', [SnippetController::class, 'edit'])->name('snippets.edit');
    Route::put('/snippets/{snippet}', [SnippetController::class, 'update'])->name('snippets.update');
    Route::delete('/snippets/{snippet}', [SnippetController::class, 'destroy'])->name('snippets.destroy');
});
Route::get('/snippets/{snippet:slug}', [SnippetController::class, 'show'])->name('snippets.show');

// Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');

// Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/advertise', [PageController::class, 'advertise'])->name('advertise');
Route::post('/advertise', [PageController::class, 'advertiseContact'])->name('advertise.contact');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
