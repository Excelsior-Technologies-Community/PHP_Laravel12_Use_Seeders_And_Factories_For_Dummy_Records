<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SeederStudioController extends Controller
{
    /**
     * Display the Seeder & Factory Studio Dashboard
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_posts' => Post::count(),
            'published_posts' => Post::where('status', 'published')->count(),
            'draft_posts' => Post::where('status', 'draft')->count(),
            'archived_posts' => Post::where('status', 'archived')->count(),
            'total_categories' => Category::count(),
            'total_comments' => Comment::count(),
            'approved_comments' => Comment::where('is_approved', 1)->count(),
            'pending_comments' => Comment::where('is_approved', 0)->count(),
            'total_views' => Post::sum('views') ?? 0,
        ];

        // Distribution stats for visual charts
        $postsByStatus = [
            'Published' => $stats['published_posts'],
            'Draft' => $stats['draft_posts'],
            'Archived' => $stats['archived_posts'],
        ];

        $commentsByApproval = [
            'Approved' => $stats['approved_comments'],
            'Pending' => $stats['pending_comments'],
        ];

        $topCategories = Category::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(5)
            ->get();

        $topAuthors = User::withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(5)
            ->get();

        $recentPosts = Post::with('user')->latest()->limit(5)->get();

        return view('seeder_studio.index', compact(
            'stats',
            'postsByStatus',
            'commentsByApproval',
            'topCategories',
            'topAuthors',
            'recentPosts'
        ));
    }

    /**
     * Custom Batch Record Generator
     */
    public function generateBatch(Request $request)
    {
        $validated = $request->validate([
            'users_count' => 'required|integer|min:0|max:1000',
            'categories_count' => 'required|integer|min:0|max:500',
            'posts_count' => 'required|integer|min:0|max:2000',
            'comments_count' => 'required|integer|min:0|max:5000',
        ]);

        $startTime = microtime(true);
        $logs = [];

        // 1. Generate Categories
        $categoriesCreated = 0;
        if ($validated['categories_count'] > 0) {
            Category::factory()->count($validated['categories_count'])->create();
            $categoriesCreated = $validated['categories_count'];
            $logs[] = "Generated {$categoriesCreated} Categories successfully.";
        }

        // 2. Generate Users
        $usersCreated = 0;
        if ($validated['users_count'] > 0) {
            User::factory()->count($validated['users_count'])->create();
            $usersCreated = $validated['users_count'];
            $logs[] = "Generated {$usersCreated} Users successfully.";
        }

        // Fetch existing users and categories to attach to posts
        $users = User::all();
        $categories = Category::all();

        if ($users->isEmpty()) {
            $users = User::factory()->count(5)->create();
            $logs[] = "Auto-created 5 fallback Users for Post relationships.";
        }

        if ($categories->isEmpty()) {
            $categories = Category::factory()->count(3)->create();
            $logs[] = "Auto-created 3 fallback Categories for Post relationships.";
        }

        // 3. Generate Posts
        $postsCreated = 0;
        if ($validated['posts_count'] > 0) {
            for ($i = 0; $i < $validated['posts_count']; $i++) {
                $user = $users->random();
                $post = Post::factory()->create([
                    'user_id' => $user->id,
                ]);

                // Attach 1 to 3 random categories
                $randomCategories = $categories->random(min(rand(1, 3), $categories->count()));
                $post->categories()->attach($randomCategories->pluck('id'));
                $postsCreated++;
            }
            $logs[] = "Generated {$postsCreated} Posts with Category relationships.";
        }

        // Fetch posts for comments
        $posts = Post::all();

        // 4. Generate Comments
        $commentsCreated = 0;
        if ($validated['comments_count'] > 0 && $posts->isNotEmpty()) {
            for ($i = 0; $i < $validated['comments_count']; $i++) {
                $post = $posts->random();
                $user = $users->random();
                Comment::factory()->create([
                    'post_id' => $post->id,
                    'user_id' => $user->id,
                ]);
                $commentsCreated++;
            }
            $logs[] = "Generated {$commentsCreated} Comments linked to Posts & Users.";
        }

        $executionTime = round(microtime(true) - $startTime, 2);
        $totalRecords = $usersCreated + $categoriesCreated + $postsCreated + $commentsCreated;
        $recordsPerSec = $executionTime > 0 ? round($totalRecords / $executionTime, 1) : $totalRecords;

        $message = "Batch generation complete! Created {$totalRecords} total records in {$executionTime}s ({$recordsPerSec} rec/sec).";

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'logs' => $logs,
                'execution_time' => $executionTime,
                'total_records' => $totalRecords,
                'records_per_sec' => $recordsPerSec,
            ]);
        }

        return redirect()->route('seeder.studio')->with([
            'success' => $message,
            'logs' => $logs,
        ]);
    }

    /**
     * Apply Preset Scenario
     */
    public function applyPreset(Request $request)
    {
        $preset = $request->input('preset', 'ecommerce');

        $configs = [
            'ecommerce' => ['users_count' => 50, 'categories_count' => 15, 'posts_count' => 200, 'comments_count' => 300],
            'benchmark' => ['users_count' => 100, 'categories_count' => 20, 'posts_count' => 500, 'comments_count' => 1000],
            'blog' => ['users_count' => 10, 'categories_count' => 5, 'posts_count' => 50, 'comments_count' => 150],
            'community' => ['users_count' => 100, 'categories_count' => 5, 'posts_count' => 20, 'comments_count' => 500],
        ];

        $config = $configs[$preset] ?? $configs['ecommerce'];
        $request->merge($config);

        return $this->generateBatch($request);
    }

    /**
     * 1-Click Database Reset & Re-Seed
     */
    public function resetDatabase(Request $request)
    {
        $startTime = microtime(true);
        
        try {
            Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
            $output = Artisan::output();
            $executionTime = round(microtime(true) - $startTime, 2);

            $message = "Database reset & fresh seeding executed successfully in {$executionTime} seconds!";

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'output' => $output,
                ]);
            }

            return redirect()->route('seeder.studio')->with('success', $message);
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Reset failed: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->route('seeder.studio')->with('error', 'Reset failed: ' . $e->getMessage());
        }
    }

    /**
     * Multi-Format Dataset Exporter (JSON, CSV, XLSX)
     */
    public function exportDataset(Request $request)
    {
        $format = strtolower($request->query('format', 'csv'));
        $entity = strtolower($request->query('entity', 'posts'));
        $filename = "seeded_{$entity}_" . date('Y_m_d_His') . ".{$format}";

        $queryData = match ($entity) {
            'users' => User::select('id', 'name', 'email', 'created_at')->get(),
            'categories' => Category::select('id', 'name', 'slug', 'description', 'created_at')->get(),
            'comments' => Comment::with(['user:id,name', 'post:id,title'])->get()->map(function ($c) {
                return [
                    'id' => $c->id,
                    'author' => $c->user->name ?? 'N/A',
                    'post' => $c->post->title ?? 'N/A',
                    'content' => Str::limit($c->content, 100),
                    'approved' => $c->is_approved ? 'Yes' : 'No',
                    'created_at' => $c->created_at?->toDateTimeString(),
                ];
            }),
            default => Post::with('user:id,name')->get()->map(function ($p) {
                return [
                    'id' => $p->id,
                    'title' => $p->title,
                    'slug' => $p->slug,
                    'author' => $p->user->name ?? 'N/A',
                    'status' => $p->status,
                    'views' => $p->views,
                    'created_at' => $p->created_at?->toDateTimeString(),
                ];
            }),
        };

        if ($format === 'json') {
            $headers = [
                'Content-Type' => 'application/json',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];
            return response()->json($queryData, 200, $headers);
        }

        // CSV or XLSX download streaming
        $headers = [
            'Content-Type' => $format === 'xlsx' ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' : 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($queryData) {
            $file = fopen('php://output', 'w');
            
            if ($queryData->isNotEmpty()) {
                $firstRow = (array) $queryData->first();
                fputcsv($file, array_keys($firstRow));

                foreach ($queryData as $row) {
                    fputcsv($file, (array) $row);
                }
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
