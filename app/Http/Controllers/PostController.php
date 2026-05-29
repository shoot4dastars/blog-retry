<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        //
        $posts = Post::published()
            ->with(['user','categories'])
            ->latest()
            ->paginate(8);

        return view('posts.index',compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $categories = Category::all();
        return view('posts.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();

        $post = Post::create([
            'title' => $validated['title'],
            'slug' => $this->generateUniqueSlug($validated['title']),
            'body' => $validated['body'],
            'user_id' => auth()->id(),
        ]);

        if (!empty($validated['category_ids'])) {
            $post->categories()->attach($validated['category_ids']);
        }

        $post->status()->create([
            'status' => $validated['status'] ?? 'draft'
        ]);

        return redirect()->route('posts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
        $post->increment('view_count');

        $post->load(['user', 'categories', 'status']);

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
        $categories = Category::all();

        $post->load('categories');

        return view('posts.edit', compact('post', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $validated = $request->validated();
        $slug = $post->slug;

        if (isset($validated['title']) && $validated['title'] !== $post->title) {
            $slug = $this->generateUniqueSlug($validated['title']);
        }

        $post->update([
            'title' => $validated['title'],
            'slug' => $slug,
            'body' => $validated['body'],
        ]);

        if (!empty($validated['category_ids'])) {
            $post->categories()->sync($validated['category_ids']);
        } else {
            $post->categories()->detach();
        }

        $post->status()->updateOrCreate(
            [],
            [
                'status' => $validated['status'] ?? 'draft'
            ]
        );

        return redirect()->route('posts.index')
            ->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
        $post->categories()->detach();
        $post->status()->delete();
        $post->delete();

        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully.');
    }

    private function generateUniqueSlug(string $title): string
    {
        $slug = \Str::slug($title);
        $count = Post::where('slug', 'LIKE', "{$slug}%")->count();
        if ($count) {$slug .= '-' . ($count + 1);}
        return $slug;
    }
}
