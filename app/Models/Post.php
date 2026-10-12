<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'excerpt',
        'content',
        'cover_image',
        'youtube_url',
        'author_name',
        'author_avatar',
        'reading_time_minutes',
        'is_featured',
        'is_published',
        'published_at',
        'faqs',
        'seo_title',
        'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'faqs' => 'array',
            'reading_time_minutes' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            if (empty($post->slug) && filled($post->title)) {
                $post->slug = Str::slug($post->title);
            }

            if (filled($post->content) && ($post->reading_time_minutes === null || $post->reading_time_minutes <= 0)) {
                $wordCount = str_word_count(strip_tags($post->content));
                $post->reading_time_minutes = max(1, (int) ceil($wordCount / 200));
            }
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        if (blank($this->cover_image)) {
            return null;
        }

        if (Str::startsWith($this->cover_image, ['http://', 'https://'])) {
            return $this->cover_image;
        }

        return Storage::disk('public')->url($this->cover_image);
    }

    public function getYoutubeIdAttribute(): ?string
    {
        if (blank($this->youtube_url)) {
            return null;
        }

        $url = trim($this->youtube_url);

        // Pattern matching standard formats: watch?v=ID, youtu.be/ID, embed/ID
        if (preg_match('/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
