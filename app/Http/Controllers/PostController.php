<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostView;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use AuthorizesRequests;
    private function generateUniqueSlug(string $title): string
    {
        $slug = \Str::slug($title);
        $count = Post::where('slug', 'LIKE', "{$slug}%")->count();
        if ($count) {$slug .= '-' . ($count + 1);}
        return $slug;
    }

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
        $user = $request->user();
        $post = Post::create([
            'title' => $validated['title'],
            'slug' => $this->generateUniqueSlug($validated['title']),
            'body' => $validated['body'],
            'user_id' => $user->id,
        ]);

        if (!empty($validated['category_ids'])) {
            $post->categories()->attach($validated['category_ids']);
        }

        $post->status()->create([
            'status' => $validated['status'] ?? 'draft'
        ]);

        return redirect()->route('posts.index')->with('success','Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $this->authorize('view', $post);
        $visitorId = request()->cookie('visitor_id');

        if (!$visitorId) {
            $visitorId = (string) Str::uuid();
            Cookie::queue(
                'visitor_id',
                $visitorId,
                60 * 24 * 365
            );
        }

        $alreadyViewed = PostView::where('post_id', $post->id)
            ->where(function ($q) use ($visitorId) {
                if (auth()->check()) {
                    $q->where('user_id', auth()->id());
                } else {
                    $q->where('visitor_id', $visitorId);
                }
            })
            ->exists();

        if (! $alreadyViewed) {
            PostView::create([
                'post_id'    => $post->id,
                'user_id'    => auth()->id(),
                'visitor_id' => auth()->check() ? null : $visitorId,
            ]);
            $post->increment('view_count');
            $post->refresh();
        }

        $post->load(['user', 'categories', 'status', 'comments.user']);

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

        if ($post->status) {
            $post->status->update([
                'status' => $validated['status'] ?? $post->status->status
            ]);
        } else {
            $post->status()->create([
                'status' => $validated['status'] ?? 'draft'
            ]);
        }

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

    public function dashboard()
    {
        $user = auth()->user();

        $publishedCount = Post::where('user_id', $user->id)
            ->published()
            ->count();

        $draftCount = Post::where('user_id', $user->id)
            ->draft()
            ->count();

        $pendingCount = Post::where('user_id', $user->id)
            ->submitted()
            ->count();

        return view('dashboard',compact('user', 'publishedCount', 'draftCount', 'pendingCount'));
    }

    public function myPosts()
    {
        $posts = Post::published()
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(8);
        $pageTitle = 'My Published Posts';
        $alertMsg = 'No published posts yet';

        return view('posts.index',  ['title'=>'My Posts'], compact('posts', 'pageTitle', 'alertMsg'));
    }

    public function myDrafts()
    {
        $posts = Post::draft()
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(8);
        $pageTitle = 'My Drafts';
        $alertMsg = 'No drafts yet';

        return view('posts.index',  ['title'=>'My Drafts'], compact('posts', 'pageTitle', 'alertMsg'));
    }

    public function pending()
    {
        $pageTitle = 'Pending Posts';
        $alertMsg = 'No pending posts yet';

        if (auth()->user()->hasPermissionTo('publish-posts')) {
            $posts = Post::submitted()
                ->latest()
                ->paginate(8);

        } else {
            $posts = Post::submitted()
                ->where('user_id', auth()->id())
                ->latest()
                ->paginate(8);
        }

        return view('posts.index',  ['title'=>'Pending'], compact('posts', 'pageTitle', 'alertMsg'));
    }

    public function approve(Post $post)
    {
        $post->status()->updateOrCreate(
            [],
            ['status' => 'published']
        );

        return back()->with('success', 'Post approved successfully.');
    }
}
