@extends('layouts.app')

@section('title', 'Edit Post')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-transparent border-0 pt-4">
                    <h2 class="mb-0 fw-bold">✏️ Edit Post</h2>
                    <p class="text-muted">Update your content and manage status</p>
                </div>
                <div class="card-body p-4">
                    @can('update', $post)
                        <form action="{{ route('posts.update', $post) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title', $post->title) }}">
                                @error('title')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Content</label>
                                <textarea name="body" rows="8" class="form-control">{{ old('body', $post->body) }}</textarea>
                                @error('body')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold">Categories</label>
                                <select name="category_ids[]" class="form-select" multiple size="4">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" @if($post->categories->contains($category->id)) selected @endif>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="form-text text-muted">Hold Ctrl/Cmd to select multiple</div>
                            </div>

                            <input type="hidden" name="status" id="status-input" value="{{ old('status', $post->status?->status ?? 'draft') }}">

                            <div class="d-flex gap-3 mt-4">
                                <button type="submit" class="btn btn-secondary px-4" onclick="setStatus('draft')">
                                    Update Draft
                                </button>

                                @can('publish-posts')
                                    <button type="submit" class="btn btn-success px-4" onclick="setStatus('published')">
                                        Update & Publish
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-primary px-4" onclick="setStatus('submitted')">
                                        Submit for Review
                                    </button>
                                @endcan
                            </div>
                        </form>
                    @else
                        <div class="alert alert-warning border-0 rounded-3">
                            <i class="bi bi-shield-lock-fill me-2"></i> You are not authorized to edit this post.
                        </div>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <script>
        function setStatus(value) {
            document.getElementById('status-input').value = value;
        }
    </script>
@endsection
