<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'status',
        'views',
    ];

    protected $casts = [
        'status' => 'string',
        'views' => 'integer',
    ];

    protected $appends = [
        'reading_time',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    public function scopeSearch($query, ?string $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%")
                ->orWhere('excerpt', 'like', "%{$search}%");
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Category Filter
    |--------------------------------------------------------------------------
    */

    public function scopeCategory($query, $categoryId)
    {
        if (!$categoryId) {
            return $query;
        }

        return $query->whereHas('categories', function ($q) use ($categoryId) {
            $q->where('categories.id', $categoryId);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Date Filter
    |--------------------------------------------------------------------------
    */

    public function scopeDateFrom($query, $date)
    {
        if (!$date) {
            return $query;
        }

        return $query->whereDate('created_at', '>=', $date);
    }

    public function scopeDateTo($query, $date)
    {
        if (!$date) {
            return $query;
        }

        return $query->whereDate('created_at', '<=', $date);
    }

    /*
    |--------------------------------------------------------------------------
    | Sorting
    |--------------------------------------------------------------------------
    */

    public function scopeSortBy($query, ?string $sort)
    {
        switch ($sort) {
            case 'oldest':
                return $query->orderBy('created_at', 'asc');

            case 'popular':
                return $query->orderBy('views', 'desc');

            case 'title_asc':
                return $query->orderBy('title', 'asc');

            case 'title_desc':
                return $query->orderBy('title', 'desc');

            case 'latest':
            default:
                return $query->orderBy('created_at', 'desc');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Reading Time
    |--------------------------------------------------------------------------
    */

    public function getReadingTimeAttribute(): int
    {
        $text = strip_tags($this->content ?? '');

        $words = str_word_count($text);

        return max(1, (int) ceil($words / 200));
    }

    /*
    |--------------------------------------------------------------------------
    | View Counter
    |--------------------------------------------------------------------------
    */

    public function incrementViews(): void
    {
        $this->increment('views');
    }
}
