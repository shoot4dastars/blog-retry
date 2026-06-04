@extends('layouts.app')

@section('title', $post->title)

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-4 p-lg-5">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                        <h1 class="display-6 fw-bold">{{ $post->title }}</h1>
                        @can('publish-posts')
                            @if($post->status?->status === 'submitted')
                                <form action="{{ route('posts.approve', $post) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success rounded-pill px-4">
                                        Approve Post
                                    </button>
                                </form>
                            @endif
                        @endcan
                    </div>

                    <div class="d-flex flex-wrap gap-3 align-items-center text-muted small mb-4">
                        <span><i class="bi bi-person-circle"></i> <strong>{{ $post->user->name }}</strong></span>
                        <span><i class="bi bi-clock"></i> {{ $post->created_at->diffForHumans() }}</span>
                        <span><i class="bi bi-eye"></i> {{ $post->view_count }} views</span>
                        @php $status = $post->status?->status; @endphp
                        @if($status === 'published')
                            <span class="badge bg-success"><i class="bi bi-check-circle"></i> Published</span>
                        @elseif($status === 'submitted')
                            <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split"></i> Pending Review</span>
                        @elseif($status === 'draft')
                            @can('update', $post)
                                <span class="badge bg-secondary"><i class="bi bi-file-earmark-text"></i> Draft</span>
                            @endcan
                        @endif
                    </div>

                    <div class="mb-4">
                        @foreach($post->categories as $category)
                            <span class="badge bg-light text-dark border me-1 px-3 py-2 rounded-pill">
                            #{{ $category->name }}
                        </span>
                        @endforeach
                    </div>

                    @can('view', $post)
                        <div class="mb-5 p-4 bg-light rounded-4" style="background: #f8fafc;">
                            {!! nl2br(e($post->body)) !!}
                        </div>
                    @else
                        <div class="alert alert-warning rounded-4">
                            <i class="bi bi-lock-fill"></i> This post is not publicly available.
                        </div>
                    @endcan

                    @can('view', $post)
                        <div class="d-flex gap-2 mb-5">
                            @can('update', $post)
                                <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning rounded-pill px-3">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                            @endcan
                            @can('delete', $post)
                                <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this post?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger rounded-pill px-3">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            @endcan
                        </div>
                    @endcan

                    @can('view', $post)
                        <hr class="my-5">
                        <h3 class="h4 fw-bold mb-4">
                            <i class="bi bi-chat-dots"></i> Comments ({{ $post->comments->count() }})
                        </h3>

                        @auth
                            <div class="card mb-5 border-0 shadow-sm rounded-4">
                                <div class="card-body">
                                    <form action="{{ route('comments.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                                        <div class="mb-3">
                                            <textarea name="body" class="form-control" rows="3" placeholder="Write a comment..." required>{{ old('body') }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                                            <i class="bi bi-send"></i> Post Comment
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endauth

                        @forelse($post->comments as $comment)
                            <div class="card mb-4 border-0 shadow-sm rounded-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <strong><i class="bi bi-person-badge"></i> {{ $comment->user->name }}</strong>
                                            <div class="text-muted small">
                                                <i class="bi bi-calendar3"></i> {{ $comment->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                        @auth
                                            @php
                                                $canEdit = auth()->user()->can('edit-comments') || auth()->id() === $comment->user_id;
                                                $canDelete = auth()->user()->can('delete-comments') || auth()->id() === $comment->user_id;
                                            @endphp
                                            <div class="d-flex gap-2">
                                                @if($canEdit)
                                                    <button class="btn btn-sm btn-outline-warning rounded-pill" type="button" data-bs-toggle="collapse" data-bs-target="#editComment{{ $comment->id }}">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </button>
                                                @endif
                                                @if($canDelete)
                                                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Delete this comment?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill">
                                                            <i class="bi bi-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        @endauth
                                    </div>
                                    <hr>
                                    <p class="mb-0">{!! nl2br(e($comment->body)) !!}</p>

                                    @auth
                                        @if(auth()->user()->can('edit-comments') || auth()->id() === $comment->user_id)
                                            <div class="collapse mt-3" id="editComment{{ $comment->id }}">
                                                <form action="{{ route('comments.update', $comment) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-2">
                                                        <textarea name="body" class="form-control" rows="3" required>{{ $comment->body }}</textarea>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary btn-sm rounded-pill">Save Changes</button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-light border rounded-4 text-center py-5">
                                <i class="bi bi-chat-square-text fs-1 text-muted"></i>
                                <p class="mt-2 mb-0">No comments yet. Be the first to share your thoughts!</p>
                            </div>
                        @endforelse
                    @endcan

                    <div class="mt-5 text-center">
                        <a href="{{ route('posts.index') }}" class="btn btn-link text-decoration-none">
                            <i class="bi bi-arrow-left-circle"></i> Back to posts
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
