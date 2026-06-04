@extends('layouts.app')

@section('title', 'Account Suspended')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-lg rounded-4 text-center overflow-hidden">
                <div class="card-body p-5">
                    <div class="display-1 mb-3">
                        🚫
                    </div>
                    <h1 class="h3 text-danger fw-bold mb-3">
                        Account Suspended
                    </h1>
                    <p class="text-muted mb-4">
                        Your account has been suspended and you currently do not have access to BuddhaBlogs.
                    </p>
                    <p class="small text-muted">
                        If you believe this is a mistake, please contact the site administrator for assistance.
                    </p>
                    <a href="{{ route('posts.index') }}" class="btn btn-primary rounded-pill px-4 mt-2">
                        <i class="bi bi-arrow-left-circle"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
