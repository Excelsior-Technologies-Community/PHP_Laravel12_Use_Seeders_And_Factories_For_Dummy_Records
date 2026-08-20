<?php

use App\Models\Post;
use App\Models\Category;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Global Search
|--------------------------------------------------------------------------
*/

Route::get('/global-search', function (Request $request) {

    $search = trim($request->search);

    if (!$search) {
        return response()->json([]);
    }

    $posts = Post::where('title', 'like', "%{$search}%")
        ->limit(5)
        ->get()
        ->map(function ($post) {
            return [
                'type' => 'Post',
                'title' => $post->title,
                'url' => '/post/' . $post->slug,
            ];
        });

    $users = User::where('name', 'like', "%{$search}%")
        ->limit(5)
        ->get()
        ->map(function ($user) {
            return [
                'type' => 'User',
                'title' => $user->name,
                'url' => '/author/' . $user->id,
            ];
        });

    $categories = Category::where('name', 'like', "%{$search}%")
        ->limit(5)
        ->get()
        ->map(function ($category) {
            return [
                'type' => 'Category',
                'title' => $category->name,
                'url' => '/category/' . $category->slug,
            ];
        });

    return response()->json(
        $posts->merge($users)->merge($categories)
    );
});


/*
|--------------------------------------------------------------------------
| Home - Advanced Search / Filter / Sort / Pagination
|--------------------------------------------------------------------------
*/

Route::get('/', function (Request $request) {

    $search = trim($request->search ?? '');
    $categoryId = $request->category_id;
    $status = $request->status;
    $sort = $request->sort ?? 'latest';
    $dateFrom = $request->date_from;
    $dateTo = $request->date_to;

    $posts = Post::with(['user', 'categories'])
        ->published()
        ->search($search)
        ->category($categoryId)
        ->dateFrom($dateFrom)
        ->dateTo($dateTo);

    if ($status && in_array($status, ['published', 'draft'])) {
        $posts->where('status', $status);
    }

    $posts = $posts
        ->sortBy($sort)
        ->paginate(10)
        ->withQueryString();

    $categories = Category::withCount('posts')
        ->orderBy('posts_count', 'desc')
        ->limit(10)
        ->get();

    return view('welcome', compact(
        'posts',
        'categories',
        'search',
        'categoryId',
        'status',
        'sort',
        'dateFrom',
        'dateTo'
    ));
});


/*
|--------------------------------------------------------------------------
| Post Details
|--------------------------------------------------------------------------
*/

Route::get('/post/{slug}', function ($slug) {

    $post = Post::with([
        'user',
        'categories',
        'comments' => function ($query) {
            $query->approved()
                ->whereNull('parent_id')
                ->with([
                    'user',
                    'replies' => function ($replyQuery) {
                        $replyQuery->approved()
                            ->with('user');
                    }
                ]);
        }
    ])
        ->where('slug', $slug)
        ->published()
        ->firstOrFail();

    /*
    |--------------------------------------------------------------------------
    | View Tracking
    |--------------------------------------------------------------------------
    */

    $post->incrementViews();
    $post->refresh();

    /*
    |--------------------------------------------------------------------------
    | Related Posts
    |--------------------------------------------------------------------------
    */

    $relatedPosts = Post::with('categories')
        ->whereHas('categories', function ($query) use ($post) {
            $query->whereIn(
                'categories.id',
                $post->categories->pluck('id')
            );
        })
        ->where('id', '!=', $post->id)
        ->published()
        ->orderBy('views', 'desc')
        ->limit(3)
        ->get();

    /*
    |--------------------------------------------------------------------------
    | More From Author
    |--------------------------------------------------------------------------
    */

    $authorPosts = Post::where('user_id', $post->user_id)
        ->where('id', '!=', $post->id)
        ->published()
        ->orderBy('created_at', 'desc')
        ->limit(3)
        ->get();

    return view('post', compact(
        'post',
        'relatedPosts',
        'authorPosts'
    ));
});


/*
|--------------------------------------------------------------------------
| Category
|--------------------------------------------------------------------------
*/

Route::get('/category/{slug}', function (Request $request, $slug) {

    $category = Category::with([
        'parent',
        'children'
    ])
        ->where('slug', $slug)
        ->firstOrFail();

    $search = trim($request->search ?? '');
    $sort = $request->sort ?? 'latest';
    $dateFrom = $request->date_from;
    $dateTo = $request->date_to;

    $posts = $category->posts()
        ->with(['user', 'categories'])
        ->published()
        ->search($search)
        ->dateFrom($dateFrom)
        ->dateTo($dateTo)
        ->sortBy($sort)
        ->paginate(10)
        ->withQueryString();

    $popularPosts = $category->posts()
        ->published()
        ->orderBy('views', 'desc')
        ->limit(5)
        ->get();

    return view('category', compact(
        'category',
        'posts',
        'popularPosts',
        'search',
        'sort',
        'dateFrom',
        'dateTo'
    ));
});


/*
|--------------------------------------------------------------------------
| Author
|--------------------------------------------------------------------------
*/

Route::get('/author/{id}', function (Request $request, $id) {

    $author = User::findOrFail($id);

    $search = trim($request->search ?? '');
    $sort = $request->sort ?? 'latest';

    $posts = $author->posts()
        ->with('categories')
        ->published()
        ->search($search)
        ->sortBy($sort)
        ->paginate(10)
        ->withQueryString();

    $popularPost = $author->posts()
        ->published()
        ->orderBy('views', 'desc')
        ->first();

    return view('author', compact(
        'author',
        'posts',
        'popularPost',
        'search',
        'sort'
    ));
});


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    $stats = [
        'total_posts' => Post::count(),

        'published_posts' => Post::published()->count(),

        'draft_posts' => Post::draft()->count(),

        'total_categories' => Category::count(),

        'total_comments' => Comment::count(),

        'approved_comments' => Comment::approved()->count(),

        'pending_comments' => Comment::pending()->count(),

        'total_users' => User::count(),

        'total_views' => Post::sum('views'),

        'today_posts' => Post::whereDate(
            'created_at',
            today()
        )->count(),

        'recent_posts' => Post::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get(),
    ];

    /*
    |--------------------------------------------------------------------------
    | Trending Posts
    |--------------------------------------------------------------------------
    */

    $popularPosts = Post::published()
        ->orderBy('views', 'desc')
        ->limit(5)
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Most Commented Posts
    |--------------------------------------------------------------------------
    */

    $mostCommentedPosts = Post::withCount([
        'comments' => function ($query) {
            $query->approved();
        }
    ])
        ->published()
        ->orderBy('comments_count', 'desc')
        ->limit(5)
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Popular Categories
    |--------------------------------------------------------------------------
    */

    $popularCategories = Category::withCount([
        'posts' => function ($query) {
            $query->published();
        }
    ])
        ->orderBy('posts_count', 'desc')
        ->limit(5)
        ->get();

    return view('dashboard', compact(
        'stats',
        'popularPosts',
        'mostCommentedPosts',
        'popularCategories'
    ));
});


/*
|--------------------------------------------------------------------------
| Test Data
|--------------------------------------------------------------------------
*/

Route::get('/test-data', function () {

    return response()->json([

        'users_count' => User::count(),

        'posts_count' => Post::count(),

        'published_posts' => Post::published()->count(),

        'draft_posts' => Post::draft()->count(),

        'categories_count' => Category::count(),

        'comments_count' => Comment::count(),

        'approved_comments' => Comment::approved()->count(),

        'pending_comments' => Comment::pending()->count(),

        'total_views' => Post::sum('views'),

        'recent_posts' => Post::with('user')
            ->latest()
            ->limit(3)
            ->get([
                'id',
                'title',
                'user_id',
                'status',
                'views',
                'created_at'
            ])
            ->map(function ($post) {

                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'author' => $post->user->name,
                    'status' => $post->status,
                    'views' => $post->views,
                    'reading_time' => $post->reading_time . ' min',
                    'created_at' => $post->created_at->format(
                        'Y-m-d H:i:s'
                    ),
                ];
            }),

        'categories' => Category::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(5)
            ->get([
                'id',
                'name',
                'posts_count'
            ]),
    ]);
});
