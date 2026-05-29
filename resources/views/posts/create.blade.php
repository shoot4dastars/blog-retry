<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>

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

<h1>Create Post</h1>

<form action="{{ route('posts.store') }}" method="POST">
    @csrf

    {{-- Title --}}
    <div>
        <label>Title</label>
        <input type="text" name="title" value="{{ old('title') }}">
    </div>

    {{-- Body --}}
    <div>
        <label>Content</label>
        <textarea name="body">{{ old('body') }}</textarea>
    </div>

    {{-- Categories --}}
    <div>
        <label>Categories</label>
        <select name="categories[]" multiple>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Hidden status field --}}
    <input type="hidden" name="status" id="status-input" value="published">

    {{-- Buttons --}}
    <div style="display:flex; gap:10px; margin-top:20px;">

        <button type="submit" onclick="setStatus('draft')">
            Save as Draft
        </button>

        <button type="submit" onclick="setStatus('published')">
            Publish
        </button>

    </div>
</form>

<script>
    function setStatus(value) {
        document.getElementById('status-input').value = value;
    }
</script>
</body>
</html>
