@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-transparent border-0 pt-4 text-center">
                    <i class="bi bi-person-plus-fill fs-1 text-success"></i>
                    <h3 class="mt-2 fw-bold">Create Account</h3>
                    <p class="text-muted small">Join the BuddhaBlogs community</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-fill"></i> Name
                            </label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Your full name">
                            @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

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
                            <input type="password" name="password" class="form-control" placeholder="Min. 8 characters">
                            @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-check-circle-fill"></i> Confirm Password
                            </label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success rounded-pill py-2 fw-semibold">
                                <i class="bi bi-person-plus-fill"></i> Register
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <span class="text-muted small">Already have an account?</span>
                        <a href="{{ route('login.form') }}" class="text-decoration-none fw-semibold ms-1">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
