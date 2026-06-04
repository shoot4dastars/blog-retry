@props(['post'])

<a href="{{ route('posts.show', $post) }}" class="text-decoration-none text-dark">
    <div class="card mb-4 border-0 shadow-sm rounded-4 post-card overflow-hidden">
        <div class="card-body p-4">
            <h4 class="card-title fw-bold mb-2">{{ $post->title }}</h4>

            <div class="text-muted small mb-3">
                <i class="bi bi-person-circle"></i> {{ $post->user->name }}
                <span class="mx-1">•</span>

                @php $status = $post->status?->status; @endphp

                @if($status === 'published')
                    <i class="bi bi-calendar-check"></i> Published {{ $post->created_at->diffForHumans() }}
                @elseif($status === 'submitted')
                    <i class="bi bi-hourglass-split"></i> Submitted for review {{ $post->updated_at->diffForHumans() }}
                @else
                    <i class="bi bi-pencil-square"></i> Last updated {{ $post->updated_at->diffForHumans() }}
                @endif

                <span class="mx-1">•</span>
                <i class="bi bi-eye"></i> {{ $post->view_count }} views
            </div>

            <p class="card-text text-secondary">
                {{ Str::limit($post->body, 150) }}
            </p>

            <div class="mt-3">
                @foreach($post->categories as $category)
                    <span class="badge bg-light text-dark border rounded-pill px-3 py-1 me-1">
                        #{{ $category->name }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>
</a>
