@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-transparent border-0 pt-4 text-center">
                    <i class="bi bi-key fs-1 text-warning"></i>
                    <h3 class="mt-2 fw-bold">Forgot Password</h3>
                    <p class="text-muted small">Enter your email and we'll help you reset your password.</p>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success rounded-3">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-envelope-fill"></i> Email Address
                            </label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="your@email.com">
                            @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-dark rounded-pill py-2 fw-semibold">
                                <i class="bi bi-send-fill"></i> Send Reset Link
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ route('login.form') }}" class="text-decoration-none small">
                            <i class="bi bi-arrow-left-circle"></i> Back to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
