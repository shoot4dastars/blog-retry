<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }

        form{
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label{
            font-weight: bold;
        }

        input,
        textarea,
        select{
            padding: 10px;
            font-size: 16px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        textarea{
            min-height: 200px;
            resize: vertical;
        }

        button{
            width: fit-content;
            padding: 10px 20px;
            cursor: pointer;
            border: none;
            background: #000;
            color: #fff;
            border-radius: 6px;
        }

        .error{
            color: red;
            font-size: 14px;
        }

        .hint{
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>

<h1>Edit Post</h1>

<form action="{{ route('posts.update', $post) }}" method="POST">

    @csrf
    @method('PUT')

    {{-- Title --}}
    <div>
        <label>Title</label>

        <input
            type="text"
            name="title"
            value="{{ old('title', $post->title) }}"
        >

        @error('title')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>

    {{-- Body --}}
    <div>
        <label>Content</label>

        <textarea name="body">{{ old('body', $post->body) }}</textarea>

        @error('body')
        <div class="error">{{ $message }}</div>
        @enderror
    </div>

    {{-- Categories --}}
    <div>
        <label>Categories</label>

        <select name="categories[]" multiple>

            @foreach($categories as $category)
                <option value="{{ $category->id }}"
                        @if($post->categories->contains($category->id)) selected @endif
                >
                    {{ $category->name }}
                </option>
            @endforeach

        </select>

        <div class="hint">
            Hold Ctrl (Windows) or Cmd (Mac) to select multiple
        </div>
    </div>

    {{-- Submit --}}
    <button type="submit">
        Update Post
    </button>

</form>

</body>
</html>
