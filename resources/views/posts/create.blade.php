@extends('layouts.app')

@section('title', 'Create Post')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-transparent border-0 pt-4">
                    <h2 class="mb-0 fw-bold text-gradient" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); -webkit-background-clip: text; background-clip: text; color: transparent;">Create New Post</h2>
                    <p class="text-muted mt-1">Share your thoughts with the community</p>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('posts.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Title</label>
                            <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="Give your post a catchy title">
                            @error('title')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Content</label>
                            <textarea name="body" rows="8" class="form-control" placeholder="Write your story...">{{ old('body') }}</textarea>
                            @error('body')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Categories</label>
                            <select name="category_ids[]" class="form-select" multiple size="4">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text text-muted">Hold Ctrl/Cmd to select multiple</div>
                        </div>

                        <input type="hidden" name="status" id="status-input" value="draft">

                        <div class="d-flex gap-3 mt-4">
                            <button type="submit" class="btn btn-secondary px-4" onclick="setStatus('draft')">
                                Save as Draft
                            </button>
                            @can('publish-posts')
                            <button type="submit" class="btn btn-success px-4" onclick="setStatus('published')">
                                Publish Now
                            </button>
                            @else
                                <button type="submit" class="btn btn-primary px-4" onclick="setStatus('submitted')">
                                    Submit for Review
                                </button>
                                @endcan
                        </div>
                    </form>
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
