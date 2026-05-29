<?php

namespace App\Http\Controllers;

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
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'title' => 'required|min:5',
            'body' => 'required|max:1000',
            'categories' => 'array'
        ]);

        $post = Post::create([
            'title' => $validated['title'],
            'slug' => \Str::slug($validated['title']),
            'body' => $validated['body'],
            'user_id' => auth()->id(),
        ]);

        if (isset($validated['categories'])){
            $post->categories()->attach($validated['categories']);
        }

        $post->status()->create([
            'status' => $request->status ?? 'draft'
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
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|min:5',
            'body' => 'required|max:1000',
            'categories' => 'array'
        ]);

        $post->update([
            'title' => $validated['title'],
            'slug' => \Str::slug($validated['title']),
            'body' => $validated['body'],
        ]);

        if ($request->filled('categories')) {
            $post->categories()->sync($validated['categories']);
        } else {
            $post->categories()->detach();
        }

        $post->status()->updateOrCreate(
            [], // no condition because morphOne is unique per post
            [
                'status' => $request->status ?? 'draft'
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
}
