@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h2 class="mb-0 fw-bold text-gradient" style="background: linear-gradient(135deg, #1e293b, #475569); -webkit-background-clip: text; background-clip: text; color: transparent;">
            {{ $pageTitle ?? 'Latest Posts' }}
        </h2>
        <a href="{{ route('posts.create') }}" class="btn btn-primary rounded-pill px-4">
            <i class="bi bi-pencil-square"></i> Create Post
        </a>
    </div>

    @forelse($posts as $post)
        <x-post-card :post="$post" />
    @empty
        <div class="alert alert-info border-0 rounded-4 shadow-sm">
            <i class="bi bi-info-circle-fill me-2"></i> {{ $alertMsg ?? 'No published posts found' }}
        </div>
    @endforelse

    <div class="mt-5 d-flex justify-content-center">
        {{ $posts->links() }}
    </div>
@endsection
