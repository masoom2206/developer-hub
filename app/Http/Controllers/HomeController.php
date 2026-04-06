<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Snippet;
use App\Models\Tool;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;

class HomeController extends Controller
{
    public function index()
    {
        SEOMeta::setTitle('DevHub — Tools & Resources for Developers');
        SEOMeta::setDescription('Discover tutorials, developer tools, code snippets, and everything you need to level up your development workflow.');
        SEOMeta::setKeywords(['developer tools', 'programming tutorials', 'code snippets', 'web development', 'laravel']);

        OpenGraph::setTitle('DevHub — Tools & Resources for Developers');
        OpenGraph::setDescription('Discover tutorials, developer tools, code snippets, and everything you need to level up your development workflow.');
        OpenGraph::setUrl(route('home'));
        OpenGraph::addProperty('type', 'website');

        TwitterCard::setTitle('DevHub — Tools & Resources for Developers');
        TwitterCard::setDescription('Discover tutorials, developer tools, code snippets, and everything you need to level up your development workflow.');

        $posts = Post::with(['user', 'category'])
            ->where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        $tools = Tool::where('is_featured', true)
            ->take(6)
            ->get();

        $snippets = Snippet::where('is_public', true)
            ->latest()
            ->take(3)
            ->get();

        $categories = Category::withCount('posts')->get();

        return view('home.index', compact('posts', 'tools', 'snippets', 'categories'));
    }
}
