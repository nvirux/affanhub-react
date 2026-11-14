<?php

use App\Models\Domain;
use App\Models\Owner;
use App\Models\Post;
use App\Models\Store;

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

test('central sitemap.xml does not leak merchant store domains to avoid google search console url not allowed errors', function () {
    $owner = Owner::create([
        'name' => 'Store Owner',
        'email' => 'owner_sitemap@example.com',
        'password' => bcrypt('password'),
    ]);

    $store = Store::create([
        'name' => 'Farakwai Data',
        'owner_id' => $owner->id,
        'status' => 'active',
    ]);

    Domain::create([
        'tenant_id' => $store->id,
        'domain' => 'farakwaidata.com',
        'is_primary' => true,
        'is_verified' => true,
        'is_routing_enabled' => true,
        'status' => 'verified',
    ]);

    $response = $this->get('/sitemap.xml');
    $response->assertStatus(200);

    $content = $response->getContent();
    expect($content)->not->toContain('farakwaidata.com');
});
