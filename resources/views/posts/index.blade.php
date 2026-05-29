<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }

        .post{
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .meta{
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .categories{
            margin-top: 10px;
        }

        .category{
            display: inline-block;
            background: #f2f2f2;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            margin-right: 5px;
        }

        .actions{
            margin-top: 15px;
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn{
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }

        .view{
            background: #3490dc;
            color: white;
        }

        .edit{
            background: #f0ad4e;
            color: white;
        }

        .delete{
            background: red;
            color: white;
        }

        .pagination{
            margin-top: 30px;
        }

        .pagination svg{
            width: 18px;
            height: 18px;
        }

    </style>
</head>
<body>

@auth
    <nav>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </nav>
@endauth

@if(session('success'))
    <div style="padding:10px; background:green; color:white; margin-bottom:15px;">
        {{ session('success') }}
    </div>
@endif

<h1>Latest Published Posts</h1>

@forelse($posts as $post)

    <div class="post">

        <h2>{{ $post->title }}</h2>

        <div class="meta">
            By {{ $post->user->name }}
            • {{ $post->created_at->diffForHumans() }}
            • {{ $post->view_count }} views
        </div>

        <p>
            {{ Str::limit($post->body, 150) }}
        </p>

        <div class="categories">
            @foreach($post->categories as $category)
                <span class="category">
                    {{ $category->name }}
                </span>
            @endforeach
        </div>

        {{-- Actions --}}
        <div class="actions">

            <a href="{{ route('posts.show', $post) }}" class="btn view">
                View
            </a>

            <a href="{{ route('posts.edit', $post) }}" class="btn edit">
                Edit
            </a>

            <form action="{{ route('posts.destroy', $post) }}"
                  method="POST"
                  onsubmit="return confirm('Delete this post?')">

                @csrf
                @method('DELETE')

                <button type="submit" class="btn delete">
                    Delete
                </button>

            </form>

        </div>

    </div>

@empty

    <p>No published posts found.</p>

@endforelse

<div class="pagination">
    {{ $posts->links() }}
</div>

</body>
</html>
