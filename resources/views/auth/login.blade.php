<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            max-width: 400px;
            margin: 80px auto;
        }

        input{
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }

        button{
            padding: 10px;
            width: 100%;
            background: black;
            color: white;
            border: none;
            cursor: pointer;
        }

        .error{
            color: red;
            font-size: 13px;
        }

        .success{
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<h2>Login</h2>

@if(session('success'))
    <div class="success">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <input type="email" name="email" placeholder="Email" value="{{ old('email') }}">
    @error('email')
    <div class="error">{{ $message }}</div>
    @enderror

    <input type="password" name="password" placeholder="Password">
    @error('password')
    <div class="error">{{ $message }}</div>
    @enderror

    <button type="submit">Login</button>
</form>

<p>
    Don't have an account?
    <a href="{{ route('register.form') }}">Register</a>
</p>

</body>
</html>
