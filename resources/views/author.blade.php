@extends('layouts.app')

@section('title', $author->name . ' - Author')

@section('content')

<div class="row">

    <div class="col-md-8">

        <div class="card mb-4">

            <div class="card-body">

                <div class="d-flex align-items-center">

                    <img
                        src="{{ $author->avatar }}"
                        alt="{{ $author->name }}"
                        class="rounded-circle me-4"
                        width="100"
                        height="100">


                    <div>

                        <h1 class="card-title mb-2">
                            {{ $author->name }}
                        </h1>


                        @if($author->role)

                        <span class="badge bg-primary mb-3">

                            {{ ucfirst($author->role) }}

                        </span>

                        @endif


                        @if($author->bio)

                        <p class="card-text">
                            {{ $author->bio }}
                        </p>

                        @endif


                        <div class="mt-3">

                            <span class="me-4">

                                <strong>
                                    Posts:
                                </strong>

                                {{ $author->posts()->published()->count() }}

                            </span>


                            <span class="me-4">

                                <strong>
                                    Comments:
                                </strong>

                                {{ $author->comments()->count() }}

                            </span>


                            <span>

                                <strong>
                                    Joined:
                                </strong>

                                {{ $author->created_at->format('M d, Y') }}

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <h3 class="mb-4">
            Latest Posts by {{ $author->name }}
        </h3>


        {{-- Author Search --}}

        <div class="card mb-4">

            <div class="card-body">

                <form method="GET">

                    <div class="input-group">

                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            class="form-control"
                            placeholder="Search author's posts...">


                        <select
                            name="sort"
                            class="form-select">

                            <option
                                value="latest"
                                {{ $sort === 'latest' ? 'selected' : '' }}>

                                Latest

                            </option>

                            <option
                                value="oldest"
                                {{ $sort === 'oldest' ? 'selected' : '' }}>

                                Oldest

                            </option>

                            <option
                                value="popular"
                                {{ $sort === 'popular' ? 'selected' : '' }}>

                                Popular

                            </option>

                            <option
                                value="title_asc"
                                {{ $sort === 'title_asc' ? 'selected' : '' }}>

                                A-Z

                            </option>

                        </select>


                        <button class="btn btn-primary">

                            Search

                        </button>

                    </div>

                </form>

            </div>

        </div>


        @forelse($posts as $post)

        <div class="card mb-4 shadow-sm">

            <div class="row g-0">

                <div class="col-md-4">

                    <img
                        src="{{ $post->featured_image }}"
                        class="img-fluid rounded-start h-100"
                        style="object-fit: cover;"
                        alt="{{ $post->title }}">

                </div>


                <div class="col-md-8">

                    <div class="card-body">

                        <h5 class="card-title">

                            <a
                                href="/post/{{ $post->slug }}"
                                class="text-decoration-none">

                                {{ $post->title }}

                            </a>

                        </h5>


                        <p class="card-text">

                            {{ Str::limit($post->excerpt, 150) }}

                        </p>


                        <div class="post-meta">

                            {{ $post->created_at->format('M d, Y') }}

                            <span class="mx-2">•</span>

                            {{ $post->views }} views

                            <span class="mx-2">•</span>

                            {{ $post->reading_time }} min read

                        </div>


                        <div class="mt-2">

                            @foreach($post->categories->take(3) as $category)

                            <span class="badge bg-secondary">

                                {{ $category->name }}

                            </span>

                            @endforeach

                        </div>

                    </div>

                </div>

            </div>

        </div>

        @empty

        <div class="alert alert-info">

            No posts found.

        </div>

        @endforelse


        <div class="d-flex justify-content-center">

            {{ $posts->links() }}

        </div>

    </div>


    <div class="col-md-4">

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Author Stats
                </h5>

            </div>

            <div class="card-body">

                <ul class="list-unstyled mb-0">

                    <li class="mb-2">

                        <strong>
                            Total Posts:
                        </strong>

                        {{ $author->posts()->count() }}

                    </li>


                    <li class="mb-2">

                        <strong>
                            Published:
                        </strong>

                        {{ $author->posts()->published()->count() }}

                    </li>


                    <li class="mb-2">

                        <strong>
                            Draft:
                        </strong>

                        {{ $author->posts()->draft()->count() }}

                    </li>


                    <li>

                        <strong>
                            Comments:
                        </strong>

                        {{ $author->comments()->count() }}

                    </li>

                </ul>

            </div>

        </div>


        @if($popularPost)

        <div class="card">

            <div class="card-header">

                <h5 class="mb-0">
                    Most Popular Post
                </h5>

            </div>

            <div class="card-body">

                <a
                    href="/post/{{ $popularPost->slug }}"
                    class="text-decoration-none">

                    {{ $popularPost->title }}

                </a>


                <p class="text-muted mt-2 mb-0">

                    {{ $popularPost->views }} views

                </p>

            </div>

        </div>

        @endif

    </div>

</div>

@endsection