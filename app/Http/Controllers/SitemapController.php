<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Handle the incoming request to generate the XML sitemap.
     */
    public function __invoke(Request $request): Response
    {
        $baseUrl = rtrim($request->root(), '/');

        // Fetch all published blog posts ordered by latest publication
        $posts = Post::published()
            ->orderByDesc('published_at')
            ->get(['slug', 'updated_at', 'published_at']);

        $content = view('sitemap', [
            'baseUrl' => $baseUrl,
            'posts' => $posts,
        ])->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
