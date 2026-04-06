<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Blog — Developer Articles & Tutorials';
        $description = 'Read the latest articles, tutorials, and insights on web development, Laravel, JavaScript, CSS, and more.';

        if ($request->category) {
            $title = ucfirst($request->category) . ' Articles — DevHub Blog';
            $description = "Browse articles in the {$request->category} category.";
        }
        if ($request->tag) {
            $title = "#{$request->tag} — DevHub Blog";
        }

        SEOMeta::setTitle($title);
        SEOMeta::setDescription($description);
        SEOMeta::setKeywords(['blog', 'web development', 'tutorials', 'laravel', 'javascript']);

        OpenGraph::setTitle($title);
        OpenGraph::setDescription($description);
        OpenGraph::setUrl(route('posts.index'));

        TwitterCard::setTitle($title);
        TwitterCard::setDescription($description);

        $posts = Post::with(['user', 'category', 'tags'])
            ->where('status', 'published')
            ->when($request->category, function ($query, $slug) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $slug));
            })
            ->when($request->tag, function ($query, $slug) {
                $query->whereHas('tags', fn ($q) => $q->where('slug', $slug));
            })
            ->latest()
            ->paginate(12);

        $categories = Category::withCount(['posts' => fn ($q) => $q->where('status', 'published')])->get();

        $tags = Tag::has('posts')->withCount('posts')->orderByDesc('posts_count')->take(20)->get();

        $popularPosts = Post::where('status', 'published')->orderByDesc('views')->take(5)->get();

        return view('posts.index', compact('posts', 'categories', 'tags', 'popularPosts'));
    }

    public function show(Post $post)
    {
        if ($post->status !== 'published') {
            abort(404);
        }

        $post->load(['user', 'category', 'tags', 'comments.user']);

        SEOMeta::setTitle($post->title);
        SEOMeta::setDescription($post->excerpt);
        SEOMeta::setKeywords($post->tags->pluck('name')->toArray());
        SEOMeta::addMeta('article:published_time', $post->created_at->toW3CString(), 'property');
        SEOMeta::addMeta('article:author', $post->user->name);

        OpenGraph::setTitle($post->title);
        OpenGraph::setDescription($post->excerpt);
        OpenGraph::setUrl(route('posts.show', $post->slug));
        OpenGraph::addProperty('type', 'article');
        if ($post->featured_image) {
            OpenGraph::addImage(asset('storage/' . $post->featured_image));
        }

        TwitterCard::setTitle($post->title);
        TwitterCard::setDescription($post->excerpt);
        if ($post->featured_image) {
            TwitterCard::setImage(asset('storage/' . $post->featured_image));
        }

        $relatedPosts = Post::where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->where('status', 'published')
            ->latest()
            ->take(3)
            ->get();

        $categories = Category::withCount(['posts' => fn ($q) => $q->where('status', 'published')])->get();

        $popularPosts = Post::where('status', 'published')->orderByDesc('views')->take(5)->get();

        return view('posts.show', compact('post', 'relatedPosts', 'categories', 'popularPosts'));
    }
}
