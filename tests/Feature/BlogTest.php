<?php

use App\Models\Post;

beforeEach(function () {
    // Clean up posts before each test
    Post::query()->delete();
});

test('public blog index displays published posts', function () {
    Post::create([
        'title' => 'First Published Post',
        'slug' => 'first-published-post',
        'category' => 'Guides',
        'content' => '<p>Welcome to AffanHub</p>',
        'is_published' => true,
        'published_at' => now()->subDay(),
    ]);

    Post::create([
        'title' => 'Draft Post',
        'slug' => 'draft-post',
        'category' => 'Tutorials',
        'content' => '<p>Draft</p>',
        'is_published' => false,
        'published_at' => now()->subDay(),
    ]);

    $response = $this->get('/blog');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Marketing/Blog/Index')
        ->has('posts.data', 1)
        ->where('posts.data.0.slug', 'first-published-post')
        ->has('categories')
    );
});

test('public blog post show page displays article with faqs and details', function () {
    $post = Post::create([
        'title' => 'Mastering Telecom Reselling',
        'slug' => 'mastering-telecom-reselling',
        'category' => 'Tutorials',
        'excerpt' => 'A guide for reselling telecom services.',
        'content' => '<h2>Getting Started</h2><p>Here is step 1</p>',
        'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        'is_published' => true,
        'published_at' => now()->subDay(),
        'faqs' => [
            ['question' => 'Is this free?', 'answer' => 'Yes, AffanHub has a free starter plan.'],
        ],
    ]);

    $response = $this->get("/blog/{$post->slug}");

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Marketing/Blog/Show')
        ->where('post.slug', 'mastering-telecom-reselling')
        ->where('post.youtube_id', 'dQw4w9WgXcQ')
        ->has('post.faqs', 1)
        ->where('post.faqs.0.question', 'Is this free?')
    );
});

test('draft or unpublished posts return 404', function () {
    $draft = Post::create([
        'title' => 'Secret Draft',
        'slug' => 'secret-draft',
        'category' => 'Updates',
        'content' => '<p>Hidden</p>',
        'is_published' => false,
        'published_at' => now()->subDay(),
    ]);

    $response = $this->get("/blog/{$draft->slug}");

    $response->assertStatus(404);
});

test('blog index filters posts by search and category', function () {
    Post::create([
        'title' => 'MTN Data Bundles Guide',
        'slug' => 'mtn-data-bundles-guide',
        'category' => 'Guides',
        'content' => '<p>MTN content</p>',
        'is_published' => true,
        'published_at' => now()->subDay(),
    ]);

    Post::create([
        'title' => 'Airtel API Integration',
        'slug' => 'airtel-api-integration',
        'category' => 'Tutorials',
        'content' => '<p>Airtel content</p>',
        'is_published' => true,
        'published_at' => now()->subDay(),
    ]);

    // Search for MTN
    $searchResponse = $this->get('/blog?search=MTN');
    $searchResponse->assertStatus(200);
    $searchResponse->assertInertia(fn ($page) => $page
        ->component('Marketing/Blog/Index')
        ->has('posts.data', 1)
        ->where('posts.data.0.slug', 'mtn-data-bundles-guide')
    );

    // Filter by Tutorials category
    $categoryResponse = $this->get('/blog?category=Tutorials');
    $categoryResponse->assertStatus(200);
    $categoryResponse->assertInertia(fn ($page) => $page
        ->component('Marketing/Blog/Index')
        ->has('posts.data', 1)
        ->where('posts.data.0.slug', 'airtel-api-integration')
    );
});
