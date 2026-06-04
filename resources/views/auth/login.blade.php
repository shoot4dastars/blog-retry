@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-transparent border-0 pt-4 text-center">
                    <i class="bi bi-box-arrow-in-right fs-1 text-primary"></i>
                    <h3 class="mt-2 fw-bold">Welcome Back</h3>
                    <p class="text-muted small">Login to your account</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-envelope-fill"></i> Email
                            </label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="your@email.com">
                            @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-lock-fill"></i> Password
                            </label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••">
                            @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill py-2 fw-semibold">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </button>
                        </div>
                    </form>

                    <div class="mt-3 text-center">
                        <a href="{{ route('password.request') }}" class="text-decoration-none small">
                            Forgot Password?
                        </a>
                    </div>

                    <hr class="my-4">

                    <div class="text-center">
                        <span class="text-muted small">Don't have an account?</span>
                        <a href="{{ route('register.form') }}" class="text-decoration-none fw-semibold ms-1">Register</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
