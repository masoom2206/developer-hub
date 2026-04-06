<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Snippet;
use App\Models\Tag;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SnippetController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Code Snippets — Ready-to-Use Code for Developers';
        $description = 'Browse and share code snippets in PHP, JavaScript, CSS, Python, and more. Copy-paste ready code from the community.';

        if ($request->language) {
            $title = $request->language . ' Code Snippets — DevHub';
            $description = "Browse {$request->language} code snippets shared by the developer community.";
        }

        SEOMeta::setTitle($title);
        SEOMeta::setDescription($description);
        SEOMeta::setKeywords(['code snippets', 'php snippets', 'javascript snippets', 'programming', 'code examples']);

        OpenGraph::setTitle($title);
        OpenGraph::setDescription($description);
        OpenGraph::setUrl(route('snippets.index'));

        TwitterCard::setTitle($title);
        TwitterCard::setDescription($description);

        $snippets = Snippet::with(['user', 'tags'])
            ->where('is_public', true)
            ->when($request->language, fn ($q, $lang) => $q->where('language', $lang))
            ->when($request->tag, fn ($q, $slug) => $q->whereHas('tags', fn ($t) => $t->where('slug', $slug)))
            ->when($request->search, fn ($q, $s) => $q->where('title', 'like', "%{$s}%"))
            ->latest()
            ->paginate(12);

        $languages = Snippet::where('is_public', true)
            ->select('language')
            ->distinct()
            ->orderBy('language')
            ->pluck('language');

        $tags = Tag::has('snippets')->withCount('snippets')->orderByDesc('snippets_count')->take(20)->get();

        $categories = Category::withCount(['posts' => fn ($q) => $q->where('status', 'published')])->get();

        $popularPosts = Post::where('status', 'published')->orderByDesc('views')->take(5)->get();

        return view('snippets.index', compact('snippets', 'languages', 'tags', 'categories', 'popularPosts'));
    }

    public function show(Snippet $snippet)
    {
        if (!$snippet->is_public && (!auth()->check() || auth()->id() !== $snippet->user_id)) {
            abort(404);
        }

        $snippet->load(['user', 'tags']);

        SEOMeta::setTitle($snippet->title . ' — ' . $snippet->language . ' Snippet');
        SEOMeta::setDescription($snippet->description ?: "A {$snippet->language} code snippet: {$snippet->title}");
        SEOMeta::setKeywords(array_merge([$snippet->language, 'code snippet'], $snippet->tags->pluck('name')->toArray()));

        OpenGraph::setTitle($snippet->title . ' — DevHub Snippets');
        OpenGraph::setDescription($snippet->description ?: "A {$snippet->language} code snippet: {$snippet->title}");
        OpenGraph::setUrl(route('snippets.show', $snippet->slug));

        TwitterCard::setTitle($snippet->title . ' — DevHub Snippets');
        TwitterCard::setDescription($snippet->description ?: "A {$snippet->language} code snippet: {$snippet->title}");

        $relatedSnippets = Snippet::where('id', '!=', $snippet->id)
            ->where('is_public', true)
            ->where('language', $snippet->language)
            ->latest()
            ->take(4)
            ->get();

        $categories = Category::withCount(['posts' => fn ($q) => $q->where('status', 'published')])->get();

        $popularPosts = Post::where('status', 'published')->orderByDesc('views')->take(5)->get();

        return view('snippets.show', compact('snippet', 'relatedSnippets', 'categories', 'popularPosts'));
    }

    public function create()
    {
        SEOMeta::setTitle('New Snippet — Share Code with the Community');

        return view('snippets.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'language' => 'required|string|in:PHP,JavaScript,CSS,Python,Bash,SQL,HTML,TypeScript,Vue,React',
            'code' => 'required|string',
            'description' => 'nullable|string|max:1000',
            'is_public' => 'boolean',
            'tags' => 'nullable|string',
        ]);

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (Snippet::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $snippet = Snippet::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'slug' => $slug,
            'language' => $validated['language'],
            'code' => $validated['code'],
            'description' => $validated['description'],
            'is_public' => $request->boolean('is_public', true),
        ]);

        if (!empty($validated['tags'])) {
            $tagIds = collect(explode(',', $validated['tags']))
                ->map(fn ($name) => trim($name))
                ->filter()
                ->map(fn ($name) => Tag::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name]
                )->id);
            $snippet->tags()->sync($tagIds);
        }


        return redirect()->route('snippets.show', $snippet->slug)->with('success', 'Snippet created successfully.');
    }

    public function edit(Snippet $snippet)
    {
        $this->authorizeEdit($snippet);
        $snippet->load('tags');

        SEOMeta::setTitle('Edit: ' . $snippet->title);

        return view('snippets.edit', compact('snippet'));
    }

    public function update(Request $request, Snippet $snippet)
    {
        $this->authorizeEdit($snippet);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'language' => 'required|string|in:PHP,JavaScript,CSS,Python,Bash,SQL,HTML,TypeScript,Vue,React',
            'code' => 'required|string',
            'description' => 'nullable|string|max:1000',
            'is_public' => 'boolean',
            'tags' => 'nullable|string',
        ]);

        $slug = Str::slug($validated['title']);
        if ($slug !== $snippet->slug) {
            $originalSlug = $slug;
            $counter = 1;
            while (Snippet::where('slug', $slug)->where('id', '!=', $snippet->id)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }
        }

        $snippet->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'language' => $validated['language'],
            'code' => $validated['code'],
            'description' => $validated['description'],
            'is_public' => $request->boolean('is_public', true),
        ]);

        if (isset($validated['tags'])) {
            $tagIds = collect(explode(',', $validated['tags']))
                ->map(fn ($name) => trim($name))
                ->filter()
                ->map(fn ($name) => Tag::firstOrCreate(
                    ['slug' => Str::slug($name)],
                    ['name' => $name]
                )->id);
            $snippet->tags()->sync($tagIds);
        } else {
            $snippet->tags()->detach();
        }


        return redirect()->route('snippets.show', $snippet->slug)->with('success', 'Snippet updated successfully.');
    }

    public function destroy(Snippet $snippet)
    {
        $this->authorizeEdit($snippet);
        $snippet->delete();

        return redirect()->route('snippets.index')->with('success', 'Snippet deleted successfully.');
    }

    private function authorizeEdit(Snippet $snippet): void
    {
        $user = auth()->user();
        if (!$user || ($user->id !== $snippet->user_id && $user->role !== 'admin')) {
            abort(403);
        }
    }
}
