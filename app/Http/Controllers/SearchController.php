<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Snippet;
use App\Models\Tool;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q', '');

        SEOMeta::setTitle($query ? "Search: {$query}" : 'Search');
        SEOMeta::setDescription("Search results for \"{$query}\" on DevHub.");

        if (strlen($query) < 2) {
            return view('search.index', [
                'query' => $query,
                'posts' => collect(),
                'tools' => collect(),
                'snippets' => collect(),
                'totalResults' => 0,
            ]);
        }

        $posts = Post::where('status', 'published')
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->with(['user', 'category'])
            ->latest()
            ->take(10)
            ->get();

        $tools = Tool::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->take(10)
            ->get();

        $snippets = Snippet::where('is_public', true)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->with('user')
            ->latest()
            ->take(10)
            ->get();

        $totalResults = $posts->count() + $tools->count() + $snippets->count();

        return view('search.index', compact('query', 'posts', 'tools', 'snippets', 'totalResults'));
    }
}
