@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">

    <h1 class="display-4 mb-4">
        Dashboard
    </h1>


    {{-- Global Search --}}

    <div class="card mb-4 shadow-sm search-card">

        <div class="card-body">

            <div class="search-wrapper">

                <input
                    type="text"
                    id="globalSearch"
                    class="form-control form-control-lg"
                    placeholder="Search posts, users, categories...">


                <div
                    id="searchResult"
                    class="list-group search-dropdown">
                </div>

            </div>

        </div>

    </div>


    {{-- Statistics --}}

    <div class="row mb-4">

        <div class="col-md-3">

            <div class="card bg-primary text-white">

                <div class="card-body">

                    <h6>
                        Total Posts
                    </h6>

                    <h2>
                        {{ $stats['total_posts'] }}
                    </h2>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card bg-success text-white">

                <div class="card-body">

                    <h6>
                        Published Posts
                    </h6>

                    <h2>
                        {{ $stats['published_posts'] }}
                    </h2>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card bg-warning text-dark">

                <div class="card-body">

                    <h6>
                        Draft Posts
                    </h6>

                    <h2>
                        {{ $stats['draft_posts'] }}
                    </h2>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card bg-info text-white">

                <div class="card-body">

                    <h6>
                        Total Views
                    </h6>

                    <h2>
                        {{ number_format($stats['total_views']) }}
                    </h2>

                </div>

            </div>

        </div>

    </div>


    {{-- Second Stats Row --}}

    <div class="row mb-4">

        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <h6>
                        Categories
                    </h6>

                    <h3>
                        {{ $stats['total_categories'] }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <h6>
                        Users
                    </h6>

                    <h3>
                        {{ $stats['total_users'] }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <h6>
                        Approved Comments
                    </h6>

                    <h3>
                        {{ $stats['approved_comments'] }}
                    </h3>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card">

                <div class="card-body">

                    <h6>
                        Pending Comments
                    </h6>

                    <h3>
                        {{ $stats['pending_comments'] }}
                    </h3>

                </div>

            </div>

        </div>

    </div>


    <div class="row">

        {{-- Recent Posts --}}

        <div class="col-md-8">

            <div class="card">

                <div class="card-header">

                    <h5 class="mb-0">
                        Recent Posts
                    </h5>

                </div>


                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-hover">

                            <thead>

                                <tr>

                                    <th>
                                        Title
                                    </th>

                                    <th>
                                        Author
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Views
                                    </th>

                                    <th>
                                        Reading
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                @foreach($stats['recent_posts'] as $post)

                                <tr>

                                    <td>

                                        <a
                                            href="/post/{{ $post->slug }}"
                                            class="text-decoration-none">

                                            {{ Str::limit($post->title, 35) }}

                                        </a>

                                    </td>


                                    <td>
                                        {{ $post->user->name }}
                                    </td>


                                    <td>

                                        @if($post->status === 'published')

                                        <span class="badge bg-success">
                                            Published
                                        </span>

                                        @else

                                        <span class="badge bg-warning">
                                            Draft
                                        </span>

                                        @endif

                                    </td>


                                    <td>
                                        {{ $post->views }}
                                    </td>


                                    <td>
                                        {{ $post->reading_time }} min
                                    </td>

                                </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>


        {{-- Trending Posts --}}

        <div class="col-md-4">

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        🔥 Trending Posts
                    </h5>

                </div>


                <div class="card-body">

                    @forelse($popularPosts as $popular)

                    <div class="mb-3">

                        <a
                            href="/post/{{ $popular->slug }}"
                            class="text-decoration-none">

                            {{ Str::limit($popular->title, 45) }}

                        </a>


                        <br>


                        <small class="text-muted">

                            👁 {{ $popular->views }} views

                        </small>

                    </div>

                    @empty

                    <p class="text-muted">
                        No popular posts.
                    </p>

                    @endforelse

                </div>

            </div>


            {{-- Most Commented --}}

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        💬 Most Commented
                    </h5>

                </div>


                <div class="card-body">

                    @foreach($mostCommentedPosts as $post)

                    <div class="mb-3">

                        <a
                            href="/post/{{ $post->slug }}"
                            class="text-decoration-none">

                            {{ Str::limit($post->title, 40) }}

                        </a>


                        <br>


                        <small class="text-muted">

                            {{ $post->comments_count }}
                            approved comments

                        </small>

                    </div>

                    @endforeach

                </div>

            </div>


            {{-- Popular Categories --}}

            <div class="card">

                <div class="card-header">

                    <h5 class="mb-0">
                        📂 Popular Categories
                    </h5>

                </div>


                <div class="card-body">

                    @foreach($popularCategories as $category)

                    <div class="d-flex justify-content-between mb-2">

                        <span>
                            {{ $category->name }}
                        </span>

                        <span class="badge bg-primary">

                            {{ $category->posts_count }}

                        </span>

                    </div>

                    @endforeach

                </div>

            </div>

        </div>

    </div>

</div>

@endsection


@push('styles')

<style>
    .search-wrapper {
        position: relative;
    }

    .search-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        max-height: 300px;
        overflow-y: auto;
        z-index: 99999;
        box-shadow: 0 10px 25px rgba(0, 0, 0, .15);
    }
</style>

@endpush


@push('scripts')

<script>
    const search =
        document.getElementById('globalSearch');

    const result =
        document.getElementById('searchResult');


    search.addEventListener('keyup', function() {

        const value = this.value.trim();


        if (value.length < 2) {

            result.innerHTML = '';

            return;

        }


        fetch(
                '/global-search?search=' +
                encodeURIComponent(value)
            )

            .then(response => response.json())

            .then(data => {

                result.innerHTML = '';


                if (data.length === 0) {

                    result.innerHTML = `
                <div class="list-group-item">
                    No result found
                </div>
            `;

                    return;

                }


                data.forEach(item => {

                    result.innerHTML += `

                <a href="${item.url}"
                   class="list-group-item
                          list-group-item-action">

                    <strong>
                        ${item.type}
                    </strong>

                    :
                    ${item.title}

                </a>

            `;

                });

            });

    });
</script>

@endpush