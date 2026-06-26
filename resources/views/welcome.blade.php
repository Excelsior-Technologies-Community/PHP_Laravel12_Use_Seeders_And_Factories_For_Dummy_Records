@extends('layouts.app')

@section('title', 'Home - Laravel Blog')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="mb-4">
            <h1 class="display-5">Latest Blog Posts</h1>
            <p class="lead">Discover our latest articles and tutorials</p>
        </div>

        @foreach($posts as $post)
        <div class="card mb-4 shadow-sm">
            <div class="row g-0">
                <div class="col-md-4">
                    <img src="{{ $post->featured_image }}" class="img-fluid rounded-start h-100" alt="{{ $post->title }}">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="/post/{{ $post->slug }}" class="text-decoration-none text-dark">
                                {{ $post->title }}
                            </a>
                        </h5>
                        <p class="card-text">{{ Str::limit($post->excerpt, 150) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="post-meta">
                                <img src="{{ $post->user->avatar }}" alt="{{ $post->user->name }}"
                                    class="rounded-circle me-2" width="30" height="30">
                                By {{ $post->user->name }}
                                <span class="mx-2">•</span>
                                {{ $post->created_at->format('M d, Y') }}
                                <span class="mx-2">•</span>
                                {{ $post->views }} views
                            </div>
                            <div>
                                @foreach($post->categories->take(2) as $category)
                                <span class="badge bg-secondary me-1">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        @if ($posts->hasPages())
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-body d-flex justify-content-center">
                {{ $posts->links() }}
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Categories</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @foreach($categories as $category)
                    <a href="/category/{{ $category->slug }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        {{ $category->name }}
                        <span class="badge bg-primary rounded-pill">{{ $category->posts_count }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Blog Stats</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">Total Posts: {{ \App\Models\Post::count() }}</li>
                    <li class="mb-2">Published Posts: {{ \App\Models\Post::published()->count() }}</li>
                    <li class="mb-2">Total Categories: {{ \App\Models\Category::count() }}</li>
                    <li class="mb-2">Total Comments: {{ \App\Models\Comment::count() }}</li>
                    <li>Total Users: {{ \App\Models\User::count() }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection