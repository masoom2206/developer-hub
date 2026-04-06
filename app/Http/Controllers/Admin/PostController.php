<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['category', 'user'])
            ->latest()
            ->paginate(15);

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();

        return view('admin.posts.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'excerpt' => 'required|string|max:500',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
            'is_sponsored' => 'boolean',
            'tags' => 'nullable|string',
        ]);

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('posts', 'public');
        }

        $post = Post::create([
            'user_id' => $request->user()->id,
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
            'excerpt' => $validated['excerpt'],
            'featured_image' => $imagePath,
            'status' => $validated['status'],
            'is_sponsored' => $request->boolean('is_sponsored'),
        ]);

        if (!empty($validated['tags'])) {
            $tagIds = collect(explode(',', $validated['tags']))
                ->map(fn ($name) => trim($name))
                ->filter()
                ->map(function ($name) {
                    return Tag::firstOrCreate(
                        ['slug' => Str::slug($name)],
                        ['name' => $name]
                    )->id;
                });
            $post->tags()->sync($tagIds);
        }

        return redirect()->route('admin.posts.index')->with('success', 'Post created successfully.');
    }

    public function edit(Post $post)
    {
        $categories = Category::all();
        $tags = Tag::all();
        $post->load('tags');

        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'content' => 'required|string',
            'excerpt' => 'required|string|max:500',
            'featured_image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
            'is_sponsored' => 'boolean',
            'tags' => 'nullable|string',
        ]);

        $slug = Str::slug($validated['title']);
        if ($slug !== $post->slug) {
            $originalSlug = $slug;
            $counter = 1;
            while (Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }
        }

        $imagePath = $post->featured_image;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('posts', 'public');
        }

        $post->update([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'content' => $validated['content'],
            'excerpt' => $validated['excerpt'],
            'featured_image' => $imagePath,
            'status' => $validated['status'],
            'is_sponsored' => $request->boolean('is_sponsored'),
        ]);

        if (isset($validated['tags'])) {
            $tagIds = collect(explode(',', $validated['tags']))
                ->map(fn ($name) => trim($name))
                ->filter()
                ->map(function ($name) {
                    return Tag::firstOrCreate(
                        ['slug' => Str::slug($name)],
                        ['name' => $name]
                    )->id;
                });
            $post->tags()->sync($tagIds);
        } else {
            $post->tags()->detach();
        }

        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully.');
    }
}
