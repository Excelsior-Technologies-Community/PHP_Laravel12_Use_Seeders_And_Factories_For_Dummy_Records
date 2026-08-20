@extends('layouts.app')

@section('title', 'Laravel Blog')

@section('content')

<style>
    :root {
        --primary: #6366f1;
        --primary-dark: #4f46e5;
        --secondary: #8b5cf6;
        --dark: #111827;
        --muted: #6b7280;
        --border: #e5e7eb;
        --light: #f8fafc;
        --card: #ffffff;
    }

    body {
        background: #f5f7fb;
    }

    /* =========================
       HERO
    ========================= */

    .blog-hero {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        padding: 55px 45px;
        margin-bottom: 30px;
        background:
            radial-gradient(circle at top right, rgba(255, 255, 255, .25), transparent 30%),
            linear-gradient(135deg, #4f46e5 0%, #6366f1 45%, #8b5cf6 100%);
        color: white;
        box-shadow: 0 20px 45px rgba(79, 70, 229, .22);
    }

    .blog-hero::before {
        content: "";
        position: absolute;
        width: 220px;
        height: 220px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .08);
        right: -70px;
        top: -80px;
    }

    .blog-hero::after {
        content: "";
        position: absolute;
        width: 160px;
        height: 160px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .06);
        left: 40%;
        bottom: -100px;
    }

    .hero-content {
        position: relative;
        z-index: 2;
    }

    .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 7px 14px;
        border-radius: 50px;
        background: rgba(255, 255, 255, .15);
        border: 1px solid rgba(255, 255, 255, .2);
        font-size: 13px;
        margin-bottom: 18px;
        backdrop-filter: blur(8px);
    }

    .hero-title {
        font-size: clamp(2rem, 5vw, 3.8rem);
        font-weight: 800;
        line-height: 1.05;
        margin-bottom: 15px;
        letter-spacing: -1.5px;
    }

    .hero-description {
        max-width: 650px;
        font-size: 1.05rem;
        opacity: .9;
        margin-bottom: 25px;
    }

    .hero-stats {
        display: flex;
        gap: 25px;
        flex-wrap: wrap;
    }

    .hero-stat {
        padding-right: 25px;
        border-right: 1px solid rgba(255, 255, 255, .25);
    }

    .hero-stat:last-child {
        border-right: 0;
    }

    .hero-stat strong {
        display: block;
        font-size: 1.5rem;
    }

    .hero-stat span {
        font-size: .85rem;
        opacity: .8;
    }

    /* =========================
       SEARCH CARD
    ========================= */

    .filter-card {
        border: 0;
        border-radius: 22px;
        background: white;
        box-shadow: 0 10px 35px rgba(15, 23, 42, .07);
        margin-bottom: 30px;
    }

    .filter-header {
        padding: 22px 25px 5px;
    }

    .filter-header h5 {
        font-weight: 700;
        color: var(--dark);
    }

    .filter-header p {
        color: var(--muted);
        font-size: 14px;
    }

    .filter-body {
        padding: 20px 25px 25px;
    }

    .form-label {
        font-size: 13px;
        font-weight: 700;
        color: #374151;
        margin-bottom: 7px;
    }

    .modern-input,
    .modern-select {
        min-height: 46px;
        border-radius: 12px;
        border: 1px solid var(--border);
        background: #f9fafb;
        transition: all .2s ease;
    }

    .modern-input:focus,
    .modern-select:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, .10);
        background: white;
    }

    .search-input-wrapper {
        position: relative;
    }

    .search-input-wrapper i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }

    .search-input-wrapper input {
        padding-left: 43px;
    }

    .btn-modern-primary {
        min-height: 46px;
        border: 0;
        border-radius: 12px;
        padding: 0 20px;
        font-weight: 600;
        background: linear-gradient(135deg,
                var(--primary),
                var(--secondary));
        color: white;
        transition: all .2s ease;
    }

    .btn-modern-primary:hover {
        color: white;
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(99, 102, 241, .25);
    }

    .btn-modern-reset {
        min-height: 46px;
        border-radius: 12px;
        font-weight: 600;
        padding: 0 18px;
    }

    /* =========================
       RESULT INFO
    ========================= */

    .result-banner {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        padding: 15px 18px;
        border-radius: 14px;
        background: #eef2ff;
        color: #3730a3;
        border: 1px solid #e0e7ff;
        margin-bottom: 22px;
    }

    .result-banner strong {
        font-size: 18px;
    }

    /* =========================
       POST CARD
    ========================= */

    .post-card {
        border: 0;
        border-radius: 22px;
        overflow: hidden;
        background: white;
        box-shadow: 0 8px 30px rgba(15, 23, 42, .06);
        transition: all .25s ease;
        margin-bottom: 24px;
    }

    .post-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 18px 40px rgba(15, 23, 42, .11);
    }

    .post-image-wrapper {
        height: 100%;
        min-height: 245px;
        position: relative;
        overflow: hidden;
        background: #e5e7eb;
    }

    .post-image {
        width: 100%;
        height: 100%;
        min-height: 245px;
        object-fit: cover;
        transition: transform .4s ease;
    }

    .post-card:hover .post-image {
        transform: scale(1.05);
    }

    .image-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top,
                rgba(0, 0, 0, .35),
                transparent 50%);
        pointer-events: none;
    }

    .featured-label {
        position: absolute;
        top: 15px;
        left: 15px;
        padding: 6px 11px;
        border-radius: 50px;
        background: rgba(17, 24, 39, .75);
        color: white;
        font-size: 11px;
        font-weight: 700;
        backdrop-filter: blur(8px);
    }

    .post-body {
        padding: 25px;
    }

    .post-title {
        font-size: 1.35rem;
        font-weight: 750;
        line-height: 1.3;
        margin-bottom: 10px;
    }

    .post-title a {
        color: var(--dark);
        transition: color .2s ease;
    }

    .post-title a:hover {
        color: var(--primary);
    }

    .post-excerpt {
        color: var(--muted);
        line-height: 1.7;
        font-size: 14px;
    }

    .category-badge {
        display: inline-flex;
        align-items: center;
        padding: 6px 10px;
        border-radius: 50px;
        background: #eef2ff;
        color: #4f46e5;
        font-size: 11px;
        font-weight: 700;
        margin-right: 4px;
        margin-bottom: 5px;
    }

    .post-meta-modern {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        color: #6b7280;
        font-size: 12px;
        margin-top: 15px;
    }

    .post-meta-modern span {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .author-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 5px;
    }

    .read-more-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border-radius: 11px;
        padding: 9px 15px;
        margin-top: 18px;
        font-size: 13px;
        font-weight: 700;
        background: #eef2ff;
        color: #4f46e5;
        text-decoration: none;
        transition: all .2s ease;
    }

    .read-more-btn:hover {
        background: #4f46e5;
        color: white;
        transform: translateX(2px);
    }

    /* =========================
       SIDEBAR
    ========================= */

    .sidebar-card {
        border: 0;
        border-radius: 20px;
        background: white;
        box-shadow: 0 8px 30px rgba(15, 23, 42, .06);
        margin-bottom: 22px;
        overflow: hidden;
    }

    .sidebar-card-header {
        padding: 20px 22px;
        border-bottom: 1px solid #f0f1f5;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .sidebar-card-header h5 {
        font-weight: 750;
        margin: 0;
        color: var(--dark);
    }

    .sidebar-card-body {
        padding: 20px 22px;
    }

    .category-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 11px 12px;
        border-radius: 12px;
        text-decoration: none;
        color: #374151;
        transition: all .2s ease;
        margin-bottom: 5px;
    }

    .category-item:hover {
        background: #f5f3ff;
        color: var(--primary);
        transform: translateX(3px);
    }

    .category-name {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 600;
    }

    .category-icon {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eef2ff;
        color: #6366f1;
        font-size: 12px;
    }

    .category-count {
        min-width: 28px;
        text-align: center;
        padding: 4px 7px;
        border-radius: 50px;
        background: #f3f4f6;
        color: #6b7280;
        font-size: 11px;
        font-weight: 700;
    }

    .feature-item {
        display: flex;
        gap: 12px;
        margin-bottom: 17px;
    }

    .feature-item:last-child {
        margin-bottom: 0;
    }

    .feature-icon {
        width: 36px;
        height: 36px;
        min-width: 36px;
        border-radius: 11px;
        background: #eef2ff;
        color: #6366f1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .feature-item strong {
        display: block;
        color: #374151;
        font-size: 13px;
        margin-bottom: 2px;
    }

    .feature-item small {
        color: #9ca3af;
        font-size: 11px;
    }

    /* =========================
       EMPTY STATE
    ========================= */

    .empty-state {
        padding: 60px 25px;
        text-align: center;
        border-radius: 22px;
        background: white;
        box-shadow: 0 8px 30px rgba(15, 23, 42, .06);
    }

    .empty-icon {
        width: 70px;
        height: 70px;
        border-radius: 20px;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eef2ff;
        color: #6366f1;
        font-size: 28px;
    }

    .empty-state h4 {
        font-weight: 750;
        color: var(--dark);
    }

    .empty-state p {
        color: var(--muted);
    }

    /* =========================
       PAGINATION
    ========================= */

    .pagination-wrapper {
        margin-top: 25px;
        display: flex;
        justify-content: center;
    }

    .pagination {
        gap: 5px;
    }

    .page-link {
        border: 0 !important;
        border-radius: 10px !important;
        color: #4b5563;
        font-weight: 600;
        min-width: 38px;
        text-align: center;
    }

    .page-item.active .page-link {
        background: #6366f1;
        color: white;
    }

    .page-link:hover {
        background: #eef2ff;
        color: #4f46e5;
    }

    /* =========================
       DARK MODE
    ========================= */

    body.dark-mode {
        background: #0b1120;
    }

    .dark-mode .filter-card,
    .dark-mode .post-card,
    .dark-mode .sidebar-card,
    .dark-mode .empty-state {
        background: #111827;
        color: #f9fafb;
    }

    .dark-mode .filter-header h5,
    .dark-mode .post-title a,
    .dark-mode .sidebar-card-header h5,
    .dark-mode .empty-state h4 {
        color: #f9fafb;
    }

    .dark-mode .form-label {
        color: #d1d5db;
    }

    .dark-mode .modern-input,
    .dark-mode .modern-select {
        background: #1f2937;
        color: white;
        border-color: #374151;
    }

    .dark-mode .post-excerpt,
    .dark-mode .post-meta-modern,
    .dark-mode .sidebar-card-body {
        color: #9ca3af;
    }

    .dark-mode .category-item {
        color: #d1d5db;
    }

    .dark-mode .category-item:hover {
        background: #1f2937;
    }

    .dark-mode .category-count {
        background: #1f2937;
        color: #9ca3af;
    }

    .dark-mode .result-banner {
        background: #1e1b4b;
        border-color: #312e81;
        color: #c7d2fe;
    }

    .dark-mode .category-badge,
    .dark-mode .read-more-btn,
    .dark-mode .feature-icon,
    .dark-mode .category-icon,
    .dark-mode .empty-icon {
        background: #1e1b4b;
        color: #a5b4fc;
    }

    /* =========================
       RESPONSIVE
    ========================= */

    @media (max-width: 991px) {
        .blog-hero {
            padding: 40px 30px;
        }

        .hero-title {
            font-size: 2.5rem;
        }

        .sidebar-card {
            margin-top: 10px;
        }
    }

    @media (max-width: 767px) {
        .blog-hero {
            padding: 35px 22px;
            border-radius: 20px;
        }

        .hero-title {
            font-size: 2.2rem;
        }

        .hero-stats {
            gap: 15px;
        }

        .hero-stat {
            padding-right: 15px;
        }

        .filter-body,
        .filter-header {
            padding-left: 18px;
            padding-right: 18px;
        }

        .post-image-wrapper,
        .post-image {
            min-height: 220px;
        }

        .post-body {
            padding: 20px;
        }

        .result-banner {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>


{{-- ============================================================
     HERO SECTION
============================================================ --}}

<div class="blog-hero">

    <div class="hero-content">

        <div class="hero-badge">
            <i class="fas fa-sparkles"></i>
            Laravel 12 Blog
        </div>

        <h1 class="hero-title">
            Discover Ideas.<br>
            Read. Learn. Grow.
        </h1>

        <p class="hero-description">
            Explore the latest articles, development tips, tutorials
            and insights from our community.
        </p>

        <div class="hero-stats">

            <div class="hero-stat">
                <strong>{{ $posts->total() }}</strong>
                <span>Articles</span>
            </div>

            <div class="hero-stat">
                <strong>{{ $categories->count() }}</strong>
                <span>Categories</span>
            </div>

            <div class="hero-stat">
                <strong>12</strong>
                <span>Laravel</span>
            </div>

        </div>

    </div>

</div>


{{-- ============================================================
     ADVANCED FILTER
============================================================ --}}

<div class="filter-card">

    <div class="filter-header">

        <h5>
            <i class="fas fa-sliders-h text-primary me-2"></i>
            Find the perfect article
        </h5>

        <p class="mb-0">
            Search, filter and sort posts to quickly find what you're looking for.
        </p>

    </div>


    <div class="filter-body">

        <form method="GET" action="/">

            <div class="row g-3">

                {{-- Search --}}
                <div class="col-lg-4 col-md-6">

                    <label class="form-label">
                        Search
                    </label>

                    <div class="search-input-wrapper">

                        <i class="fas fa-search"></i>

                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            class="form-control modern-input"
                            placeholder="Search title or content...">

                    </div>

                </div>


                {{-- Category --}}
                <div class="col-lg-2 col-md-6">

                    <label class="form-label">
                        Category
                    </label>

                    <select
                        name="category_id"
                        class="form-select modern-select">

                        <option value="">
                            All Categories
                        </option>

                        @foreach($categories as $category)

                        <option
                            value="{{ $category->id }}"
                            {{ $categoryId == $category->id ? 'selected' : '' }}>

                            {{ $category->name }}

                        </option>

                        @endforeach

                    </select>

                </div>


                {{-- From --}}
                <div class="col-lg-2 col-md-6">

                    <label class="form-label">
                        From Date
                    </label>

                    <input
                        type="date"
                        name="date_from"
                        value="{{ $dateFrom }}"
                        class="form-control modern-input">

                </div>


                {{-- To --}}
                <div class="col-lg-2 col-md-6">

                    <label class="form-label">
                        To Date
                    </label>

                    <input
                        type="date"
                        name="date_to"
                        value="{{ $dateTo }}"
                        class="form-control modern-input">

                </div>


                {{-- Sort --}}
                <div class="col-lg-2 col-md-6">

                    <label class="form-label">
                        Sort By
                    </label>

                    <select
                        name="sort"
                        class="form-select modern-select">

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
                            Most Viewed
                        </option>

                        <option
                            value="title_asc"
                            {{ $sort === 'title_asc' ? 'selected' : '' }}>
                            Title A-Z
                        </option>

                        <option
                            value="title_desc"
                            {{ $sort === 'title_desc' ? 'selected' : '' }}>
                            Title Z-A
                        </option>

                    </select>

                </div>

            </div>


            <div class="d-flex gap-2 mt-4 flex-wrap">

                <button
                    type="submit"
                    class="btn btn-modern-primary">

                    <i class="fas fa-search me-2"></i>
                    Apply Filters

                </button>

                <a
                    href="/"
                    class="btn btn-outline-secondary btn-modern-reset">

                    <i class="fas fa-rotate-left me-2"></i>
                    Reset

                </a>

            </div>

        </form>

    </div>

</div>


{{-- ============================================================
     RESULT INFORMATION
============================================================ --}}

@if($search || $categoryId || $dateFrom || $dateTo || $sort !== 'latest')

<div class="result-banner">

    <div>

        <i class="fas fa-circle-info me-2"></i>

        Found
        <strong>{{ $posts->total() }}</strong>
        matching posts

    </div>

    <a
        href="/"
        class="text-decoration-none fw-bold">

        Clear Filters
        <i class="fas fa-arrow-right ms-1"></i>

    </a>

</div>

@endif


{{-- ============================================================
     POSTS + SIDEBAR
============================================================ --}}

<div class="row g-4">

    {{-- POSTS --}}
    <div class="col-lg-8">

        @forelse($posts as $post)

        <article class="post-card">

            <div class="row g-0">

                {{-- IMAGE --}}
                <div class="col-md-5">

                    <div class="post-image-wrapper">

                        <img
                            src="{{ $post->featured_image }}"
                            class="post-image"
                            alt="{{ $post->title }}">

                        <div class="image-overlay"></div>

                        <span class="featured-label">

                            <i class="fas fa-newspaper me-1"></i>
                            Article

                        </span>

                    </div>

                </div>


                {{-- CONTENT --}}
                <div class="col-md-7">

                    <div class="post-body">

                        {{-- Categories --}}
                        <div class="mb-2">

                            @foreach($post->categories->take(3) as $category)

                            <span class="category-badge">

                                <i class="fas fa-tag me-1"></i>
                                {{ $category->name }}

                            </span>

                            @endforeach

                        </div>


                        {{-- Title --}}
                        <h2 class="post-title">

                            <a
                                href="/post/{{ $post->slug }}"
                                class="text-decoration-none">

                                {{ $post->title }}

                            </a>

                        </h2>


                        {{-- Excerpt --}}
                        <p class="post-excerpt mb-0">

                            {{ Str::limit($post->excerpt, 150) }}

                        </p>


                        {{-- Meta --}}
                        <div class="post-meta-modern">

                            <span>

                                @if($post->user->avatar)

                                <img
                                    src="{{ $post->user->avatar }}"
                                    class="author-avatar"
                                    alt="{{ $post->user->name }}">

                                @endif

                                {{ $post->user->name }}

                            </span>

                            <span>
                                <i class="far fa-calendar"></i>
                                {{ $post->created_at->format('M d, Y') }}
                            </span>

                            <span>
                                <i class="far fa-eye"></i>
                                {{ number_format($post->views) }}
                            </span>

                            <span>
                                <i class="far fa-clock"></i>
                                {{ $post->reading_time ?? 1 }} min
                            </span>

                        </div>


                        {{-- Read More --}}
                        <a
                            href="/post/{{ $post->slug }}"
                            class="read-more-btn">

                            Read Article

                            <i class="fas fa-arrow-right"></i>

                        </a>

                    </div>

                </div>

            </div>

        </article>

        @empty

        <div class="empty-state">

            <div class="empty-icon">

                <i class="fas fa-search"></i>

            </div>

            <h4>
                No articles found
            </h4>

            <p>
                We couldn't find any posts matching your search.
                Try changing your filters.
            </p>

            <a
                href="/"
                class="btn btn-modern-primary">

                <i class="fas fa-rotate-left me-2"></i>
                Clear Filters

            </a>

        </div>

        @endforelse


        {{-- Pagination --}}
        @if($posts->hasPages())

        <div class="pagination-wrapper">

            {{ $posts->appends(request()->query())->links() }}

        </div>

        @endif

    </div>


    {{-- ========================================================
         SIDEBAR
    ======================================================== --}}

    <div class="col-lg-4">


        {{-- Popular Categories --}}
        <div class="sidebar-card">

            <div class="sidebar-card-header">

                <h5>
                    <i class="fas fa-layer-group text-primary me-2"></i>
                    Popular Categories
                </h5>

            </div>

            <div class="sidebar-card-body">

                @forelse($categories as $category)

                <a
                    href="/category/{{ $category->slug }}"
                    class="category-item">

                    <span class="category-name">

                        <span class="category-icon">

                            <i class="fas fa-folder"></i>

                        </span>

                        {{ $category->name }}

                    </span>

                    <span class="category-count">

                        {{ $category->posts_count }}

                    </span>

                </a>

                @empty

                <p class="text-muted mb-0">
                    No categories available.
                </p>

                @endforelse

            </div>

        </div>


        {{-- Blog Features --}}
        <div class="sidebar-card">

            <div class="sidebar-card-header">

                <h5>
                    <i class="fas fa-bolt text-warning me-2"></i>
                    Blog Features
                </h5>

            </div>

            <div class="sidebar-card-body">


                <div class="feature-item">

                    <div class="feature-icon">
                        <i class="fas fa-search"></i>
                    </div>

                    <div>

                        <strong>
                            Advanced Search
                        </strong>

                        <small>
                            Quickly find articles
                        </small>

                    </div>

                </div>


                <div class="feature-item">

                    <div class="feature-icon">
                        <i class="fas fa-filter"></i>
                    </div>

                    <div>

                        <strong>
                            Smart Filters
                        </strong>

                        <small>
                            Category and date filters
                        </small>

                    </div>

                </div>


                <div class="feature-item">

                    <div class="feature-icon">
                        <i class="fas fa-sort"></i>
                    </div>

                    <div>

                        <strong>
                            Multiple Sorting
                        </strong>

                        <small>
                            Sort by date, views and title
                        </small>

                    </div>

                </div>


                <div class="feature-item">

                    <div class="feature-icon">
                        <i class="fas fa-clock"></i>
                    </div>

                    <div>

                        <strong>
                            Reading Time
                        </strong>

                        <small>
                            Know how long articles take
                        </small>

                    </div>

                </div>


                <div class="feature-item">

                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>

                    <div>

                        <strong>
                            View Tracking
                        </strong>

                        <small>
                            Monitor article popularity
                        </small>

                    </div>

                </div>


                <div class="feature-item">

                    <div class="feature-icon">
                        <i class="fas fa-share-nodes"></i>
                    </div>

                    <div>

                        <strong>
                            Social Sharing
                        </strong>

                        <small>
                            Share articles easily
                        </small>

                    </div>

                </div>

            </div>

        </div>


        {{-- Quick Info --}}
        <div
            class="sidebar-card"
            style="
                background: linear-gradient(
                    135deg,
                    #4f46e5,
                    #8b5cf6
                );
                color: white;
            ">

            <div class="sidebar-card-body">

                <div class="d-flex align-items-center mb-3">

                    <div
                        style="
                            width:45px;
                            height:45px;
                            border-radius:14px;
                            background:rgba(255,255,255,.15);
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            margin-right:12px;
                        ">

                        <i class="fas fa-lightbulb"></i>

                    </div>

                    <div>

                        <h6 class="mb-0 fw-bold">
                            Developer Tip
                        </h6>

                        <small style="opacity:.75;">
                            Keep learning every day
                        </small>

                    </div>

                </div>

                <p
                    class="mb-0"
                    style="font-size:13px;opacity:.85;line-height:1.7;">

                    Explore Laravel tutorials, practical examples
                    and development tips to improve your skills.

                </p>

            </div>

        </div>

    </div>

</div>

@endsection