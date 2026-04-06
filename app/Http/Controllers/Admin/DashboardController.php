<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\NewsletterSubscriber;
use App\Models\Post;
use App\Models\SponsoredAd;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_posts' => Post::count(),
            'total_views' => Post::sum('views'),
            'subscribers' => NewsletterSubscriber::where('status', 'active')->count(),
            'active_sponsors' => SponsoredAd::whereDate('starts_at', '<=', Carbon::today())
                ->whereDate('ends_at', '>=', Carbon::today())->count(),
        ];

        $recentPosts = Post::with(['user', 'category'])->latest()->take(10)->get();
        $topPosts = Post::orderByDesc('views')->take(5)->get();
        $recentComments = Comment::with(['user', 'post'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentPosts', 'topPosts', 'recentComments'));
    }

    public function deleteComment(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Comment deleted.');
    }
}
