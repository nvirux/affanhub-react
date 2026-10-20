<?php

use App\Models\Post;

beforeEach(function () {
    Post::query()->delete();
});

test('sitemap.xml returns valid xml with public marketing routes and published posts', function () {
    $publishedPost = Post::create([
        'title' => 'How to Start VTU Business',
        'slug' => 'how-to-start-vtu-business',
        'category' => 'Guides',
        'content' => '<p>Complete guide</p>',
        'is_published' => true,
        'published_at' => now()->subDay(),
    ]);

    $draftPost = Post::create([
        'title' => 'Unpublished Draft Post',
        'slug' => 'unpublished-draft-post',
        'category' => 'Drafts',
        'content' => '<p>Draft</p>',
        'is_published' => false,
        'published_at' => now()->subDay(),
    ]);

    $response = $this->get('/sitemap.xml');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

    $content = $response->getContent();

    expect($content)
        ->toContain('<?xml version="1.0" encoding="UTF-8"?>')
        ->toContain('<urlset')
        ->toContain('/blog')
        ->toContain('/privacy-policy')
        ->toContain('/blog/how-to-start-vtu-business')
        ->not->toContain('/blog/unpublished-draft-post');
});
