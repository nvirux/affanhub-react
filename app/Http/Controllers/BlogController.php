<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    /**
     * Display a listing of published blog posts.
     */
    public function index(Request $request): Response
    {
        $search = trim((string) $request->query('search', ''));
        $category = trim((string) $request->query('category', ''));

        $query = Post::published();

        if (filled($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if (filled($category) && $category !== 'All') {
            $query->where('category', $category);
        }

        $posts = $query->orderBy('is_featured', 'desc')
            ->orderBy('published_at', 'desc')
            ->paginate(12)
            ->withQueryString()
            ->through(fn (Post $post) => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'category' => $post->category,
                'excerpt' => $post->excerpt,
                'cover_image_url' => $post->cover_image_url,
                'youtube_id' => $post->youtube_id,
                'author_name' => $post->author_name,
                'reading_time_minutes' => $post->reading_time_minutes,
                'is_featured' => $post->is_featured,
                'published_at' => $post->published_at?->format('M j, Y'),
            ]);

        $categories = Post::published()
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return Inertia::render('Marketing/Blog/Index', [
            'posts' => $posts,
            'categories' => $categories,
            'filters' => [
                'search' => $search,
                'category' => $category,
            ],
        ]);
    }

    /**
     * Display the specified blog post.
     */
    public function show(string $slug): Response
    {
        $post = Post::published()
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->where(function ($q) use ($post) {
                $q->where('category', $post->category)
                    ->orWhereNotNull('published_at');
            })
            ->latest('published_at')
            ->limit(3)
            ->get()
            ->map(fn (Post $p) => [
                'id' => $p->id,
                'title' => $p->title,
                'slug' => $p->slug,
                'category' => $p->category,
                'excerpt' => $p->excerpt,
                'cover_image_url' => $p->cover_image_url,
                'reading_time_minutes' => $p->reading_time_minutes,
                'published_at' => $p->published_at?->format('M j, Y'),
            ]);

        return Inertia::render('Marketing/Blog/Show', [
            'post' => [
                'id' => $post->id,
                'title' => $post->title,
                'slug' => $post->slug,
                'category' => $post->category,
                'excerpt' => $post->excerpt,
                'content' => $post->content,
                'cover_image_url' => $post->cover_image_url,
                'youtube_id' => $post->youtube_id,
                'youtube_url' => $post->youtube_url,
                'author_name' => $post->author_name,
                'author_avatar' => $post->author_avatar,
                'reading_time_minutes' => $post->reading_time_minutes,
                'published_at' => $post->published_at?->format('F j, Y'),
                'published_at_iso' => $post->published_at?->toIso8601String(),
                'faqs' => $post->faqs ?? [],
                'seo_title' => $post->seo_title ?: $post->title,
                'seo_description' => $post->seo_description ?: $post->excerpt,
            ],
            'relatedPosts' => $relatedPosts,
        ]);
    }
}
