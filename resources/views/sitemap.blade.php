{!! '<'.'?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    {{-- Central Landing Page --}}
    <url>
        <loc>{{ $baseUrl }}/</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    {{-- Blog Knowledge Hub --}}
    <url>
        <loc>{{ $baseUrl }}/blog</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    {{-- Central Privacy Policy --}}
    <url>
        <loc>{{ $baseUrl }}/privacy-policy</loc>
        <changefreq>monthly</changefreq>
        <priority>0.3</priority>
    </url>

    {{-- Dynamic Published Blog Posts --}}
    @foreach ($posts as $post)
    <url>
        <loc>{{ $baseUrl }}/blog/{{ $post->slug }}</loc>
        @if ($post->updated_at || $post->published_at)
        <lastmod>{{ ($post->updated_at ?? $post->published_at)->toAtomString() }}</lastmod>
        @endif
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
</urlset>
