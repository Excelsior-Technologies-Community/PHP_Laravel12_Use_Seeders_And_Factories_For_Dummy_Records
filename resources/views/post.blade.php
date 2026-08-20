@extends('layouts.app')

@section('title', $post->title . ' - Laravel Blog')

@section('content')

<div class="row">

    <div class="col-lg-8">

        <article>

            {{-- Header --}}

            <header class="mb-4">

                <h1 class="fw-bold mb-3">
                    {{ $post->title }}
                </h1>


                <div class="post-meta mb-3">

                    <img
                        src="{{ $post->user->avatar }}"
                        alt="{{ $post->user->name }}"
                        class="rounded-circle me-2"
                        width="40"
                        height="40">


                    <span class="me-3">

                        By
                        <strong>
                            {{ $post->user->name }}
                        </strong>

                    </span>


                    <span class="me-3">

                        {{ $post->created_at->format('F d, Y') }}

                    </span>


                    <span class="me-3">

                        <i class="fas fa-eye"></i>

                        {{ $post->views }} views

                    </span>


                    <span>

                        <i class="fas fa-clock"></i>

                        {{ $post->reading_time }} min read

                    </span>


                    <div class="mt-3">

                        @foreach($post->categories as $category)

                        <a
                            href="/category/{{ $category->slug }}"
                            class="badge bg-primary text-decoration-none me-1">

                            {{ $category->name }}

                        </a>

                        @endforeach

                    </div>

                </div>

            </header>


            {{-- Featured Image --}}

            <figure class="mb-4">

                <img
                    class="img-fluid rounded w-100"
                    src="{{ $post->featured_image }}"
                    alt="{{ $post->title }}">

            </figure>


            {{-- Content --}}

            <section class="mb-5">

                {!! $post->content !!}

            </section>


            {{-- Share --}}

            <div class="card mb-4">

                <div class="card-header">

                    <h5 class="mb-0">
                        Share This Post
                    </h5>

                </div>

                <div class="card-body">

                    <div class="d-flex flex-wrap gap-2">

                        <a
                            href="https://wa.me/?text={{ urlencode($post->title . ' ' . url('/post/' . $post->slug)) }}"
                            target="_blank"
                            class="btn btn-success">

                            <i class="fab fa-whatsapp"></i>
                            WhatsApp

                        </a>


                        <a
                            href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/post/' . $post->slug)) }}"
                            target="_blank"
                            class="btn btn-primary">

                            <i class="fab fa-facebook"></i>
                            Facebook

                        </a>


                        <a
                            href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url('/post/' . $post->slug)) }}"
                            target="_blank"
                            class="btn btn-info">

                            <i class="fab fa-linkedin"></i>
                            LinkedIn

                        </a>


                        <button
                            type="button"
                            id="copyLinkBtn"
                            class="btn btn-outline-secondary">

                            <i class="fas fa-copy"></i>
                            Copy Link

                        </button>

                    </div>

                    <small
                        id="copyMessage"
                        class="text-success d-none">

                        Link copied successfully!

                    </small>

                </div>

            </div>


            {{-- Author --}}

            <div class="card mb-4">

                <div class="card-body">

                    <h5 class="card-title">
                        About the Author
                    </h5>

                    <div class="d-flex align-items-center">

                        <img
                            src="{{ $post->user->avatar }}"
                            alt="{{ $post->user->name }}"
                            class="rounded-circle me-3"
                            width="60"
                            height="60">


                        <div>

                            <h6>
                                {{ $post->user->name }}
                            </h6>

                            <p class="text-muted mb-0">

                                {{ $post->user->bio ?? 'No bio available' }}

                            </p>

                            <a
                                href="/author/{{ $post->user->id }}"
                                class="btn btn-sm btn-outline-primary mt-2">

                                View Profile

                            </a>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Comments --}}

            <section class="mb-5">

                <h3 class="mb-4">

                    Comments
                    ({{ $post->comments->count() }})

                </h3>


                @forelse($post->comments as $comment)

                <div class="card mb-3">

                    <div class="card-body">

                        <div class="d-flex">

                            <img
                                src="{{ $comment->user->avatar }}"
                                alt="{{ $comment->user->name }}"
                                class="rounded-circle me-3"
                                width="40"
                                height="40">


                            <div class="flex-grow-1">

                                <h6 class="mb-1">

                                    {{ $comment->user->name }}

                                </h6>


                                <small class="text-muted">

                                    {{ $comment->created_at->format('M d, Y H:i') }}

                                </small>


                                <p class="mt-2 mb-3">

                                    {{ $comment->content }}

                                </p>


                                @if($comment->replies->count() > 0)

                                <div class="ps-4 border-start">

                                    @foreach($comment->replies as $reply)

                                    <div class="mb-2">

                                        <div class="d-flex">

                                            <img
                                                src="{{ $reply->user->avatar }}"
                                                alt="{{ $reply->user->name }}"
                                                class="rounded-circle me-2"
                                                width="30"
                                                height="30">


                                            <div>

                                                <small>

                                                    <strong>
                                                        {{ $reply->user->name }}
                                                    </strong>

                                                </small>


                                                <small class="text-muted ms-2">

                                                    {{ $reply->created_at->format('M d, Y H:i') }}

                                                </small>


                                                <p class="mt-1 mb-0">

                                                    {{ $reply->content }}

                                                </p>

                                            </div>

                                        </div>

                                    </div>

                                    @endforeach

                                </div>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>

                @empty

                <div class="alert alert-info">

                    No approved comments yet.

                </div>

                @endforelse

            </section>

        </article>

    </div>


    {{-- Sidebar --}}

    <div class="col-lg-4">


        {{-- Related Posts --}}

        @if($relatedPosts->count() > 0)

        <div class="card mb-4">

            <div class="card-header">

                <h5 class="mb-0">
                    Trending Related Posts
                </h5>

            </div>


            <div class="card-body">

                @foreach($relatedPosts as $relatedPost)

                <div class="mb-3">

                    <h6 class="mb-1">

                        <a
                            href="/post/{{ $relatedPost->slug }}"
                            class="text-decoration-none">

                            {{ $relatedPost->title }}

                        </a>

                    </h6>


                    <small class="text-muted">

                        {{ $relatedPost->views }} views
                        •
                        {{ $relatedPost->created_at->format('M d, Y') }}

                    </small>

                </div>

                @endforeach

            </div>

        </div>

        @endif


        {{-- More From Author --}}

        @if($authorPosts->count() > 0)

        <div class="card">

            <div class="card-header">

                <h5 class="mb-0">
                    More From This Author
                </h5>

            </div>


            <div class="card-body">

                @foreach($authorPosts as $authorPost)

                <div class="mb-3">

                    <a
                        href="/post/{{ $authorPost->slug }}"
                        class="text-decoration-none">

                        {{ $authorPost->title }}

                    </a>

                    <br>

                    <small class="text-muted">

                        {{ $authorPost->created_at->format('M d, Y') }}

                    </small>

                </div>

                @endforeach

            </div>

        </div>

        @endif

    </div>

</div>

@endsection


@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const copyButton =
            document.getElementById('copyLinkBtn');

        const message =
            document.getElementById('copyMessage');


        if (copyButton) {

            copyButton.addEventListener('click', function() {

                navigator.clipboard.writeText(
                    window.location.href
                ).then(function() {

                    message.classList.remove('d-none');

                    setTimeout(function() {

                        message.classList.add('d-none');

                    }, 2000);

                });

            });

        }

    });
</script>

@endpush