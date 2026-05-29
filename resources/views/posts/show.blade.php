<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }}</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            line-height: 1.6;
        }

        .meta{
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .categories{
            margin: 15px 0;
        }

        .category{
            display: inline-block;
            background: #f2f2f2;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            margin-right: 5px;
        }

        .badge{
            display: inline-block;
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 12px;
            color: white;
            margin-left: 10px;
        }

        .published{
            background: green;
        }

        .draft{
            background: orange;
        }

        .actions{
            margin: 20px 0;
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

        .edit{
            background: #f0ad4e;
            color: white;
        }

        .delete{
            background: red;
            color: white;
        }

        .back{
            margin-top: 30px;
        }

        a{
            text-decoration: none;
            color: blue;
        }
    </style>
</head>
<body>

<h1>{{ $post->title }}</h1>

<div class="meta">
    By <strong>{{ $post->user->name }}</strong>
    • {{ $post->created_at->diffForHumans() }}
    • {{ $post->view_count }} views

    @if($post->status)
        <span class="badge {{ $post->status->status }}">
            {{ ucfirst($post->status->status) }}
        </span>
    @endif
</div>

{{-- Actions --}}
<div class="actions">

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

<div class="categories">
    @foreach($post->categories as $category)
        <span class="category">
            {{ $category->name }}
        </span>
    @endforeach
</div>

<div>
    {!! nl2br(e($post->body)) !!}
</div>

<div class="back">
    <a href="{{ route('posts.index') }}">← Back to posts</a>
</div>

</body>
</html>
