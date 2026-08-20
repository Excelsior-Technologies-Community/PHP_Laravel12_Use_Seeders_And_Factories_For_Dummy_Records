@extends('layouts.app')

@section('title', $category->name . ' - Category')

@section('content')

<div class="row">

    <div class="col-md-8">

        <div class="mb-4">

            <h1 class="display-5">
                {{ $category->name }}
            </h1>

            @if($category->description)

            <p class="lead">
                {{ $category->description }}
            </p>

            @endif


            @if($category->parent)

            <div class="alert alert-info">

                Parent Category:

                <a
                    href="/category/{{ $category->parent->slug }}"
                    class="text-decoration-none">

                    {{ $category->parent->name }}

                </a>

            </div>

            @endif


            @if($category->children->count() > 0)

            <div class="mb-4">

                <h5>
                    Subcategories:
                </h5>

                <div class="d-flex flex-wrap gap-2">

                    @foreach($category->children as $subcategory)

                    <a
                        href="/category/{{ $subcategory->slug }}"
                        class="badge bg-secondary text-decoration-none">

                        {{ $subcategory->name }}

                    </a>

                    @endforeach

                </div>

            </div>

            @endif

        </div>


        {{-- Category Filters --}}

        <div class="card mb-4">

            <div class="card-body">

                <form method="GET">

                    <div class="row g-2">

                        <div class="col-md-5">

                            <input
                                type="text"
                                name="search"
                                value="{{ $search }}"
                                class="form-control"
                                placeholder="Search posts...">

                        </div>


                        <div class="col-md-3">

                            <input
                                type="date"
                                name="date_from"
                                value="{{ $dateFrom }}"
                                class="form-control">

                        </div>


                        <div class="col-md-2">

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

                        </div>


                        <div class="col-md-2">

                            <button class="btn btn-primary w-100">

                                Filter

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        @if($posts->count() > 0)

        @foreach($posts as $post)

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

                    </div>

                </div>

            </div>

        </div>

        @endforeach


        <div class="d-flex justify-content-center">

            {{ $posts->links() }}

        </div>

        @else

        <div class="alert alert-info">

            No posts found in this category.

        </div>

        @endif

    </div>


    <div class="col-md-4">

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Category Info
                </h5>

            </div>

            <div class="card-body">

                <ul class="list-unstyled mb-0">

                    <li class="mb-2">

                        <strong>
                            Posts:
                        </strong>

                        {{ $posts->total() }}

                    </li>


                    @if($category->parent)

                    <li class="mb-2">

                        <strong>
                            Parent:
                        </strong>

                        {{ $category->parent->name }}

                    </li>

                    @endif


                    <li>

                        <strong>
                            Created:
                        </strong>

                        {{ $category->created_at->format('M d, Y') }}

                    </li>

                </ul>

            </div>

        </div>


        <div class="card">

            <div class="card-header">

                <h5 class="mb-0">
                    Popular in {{ $category->name }}
                </h5>

            </div>

            <div class="card-body">

                @foreach($popularPosts as $popularPost)

                <div class="mb-3">

                    <a
                        href="/post/{{ $popularPost->slug }}"
                        class="text-decoration-none">

                        {{ $popularPost->title }}

                    </a>

                    <br>

                    <small class="text-muted">

                        {{ $popularPost->views }} views

                    </small>

                </div>

                @endforeach

            </div>

        </div>

    </div>

</div>

@endsection