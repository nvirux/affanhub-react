<?php

namespace App\Http\Controllers;

use App\Http\Middleware\CheckTenantAccess;
use App\Models\Post;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Pipeline;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;

class SitemapController extends Controller
{
    /**
     * Handle the incoming request to generate the XML sitemap.
     */
    public function __invoke(Request $request): mixed
    {
        $host = $request->getHost();

        // If accessed from a tenant domain or subdomain, initialize tenant and render storefront sitemap
        if (! in_array($host, config('tenancy.central_domains', []))) {
            return app(Pipeline::class)
                ->send($request)
                ->through([
                    InitializeTenancyByDomain::class,
                    CheckTenantAccess::class,
                ])
                ->then(fn () => $this->renderTenantSitemap());
        }

        return $this->renderCentralSitemap($request);
    }

    protected function renderTenantSitemap(): Response
    {
        $store = tenant();
        $baseUrl = rtrim($store->getStoreUrl(), '/');

        $urls = [
            [
                'loc' => $baseUrl.'/',
                'lastmod' => $store->updated_at?->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            [
                'loc' => $baseUrl.'/login',
                'lastmod' => null,
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
            [
                'loc' => $baseUrl.'/register',
                'lastmod' => null,
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
            [
                'loc' => $baseUrl.'/contact',
                'lastmod' => null,
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            [
                'loc' => $baseUrl.'/privacy-policy',
                'lastmod' => null,
                'changefreq' => 'yearly',
                'priority' => '0.5',
            ],
        ];

        $content = view('sitemap-tenant', [
            'urls' => $urls,
        ])->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    protected function renderCentralSitemap(Request $request): Response
    {
        $baseUrl = rtrim($request->root(), '/');

        // Fetch all published blog posts ordered by latest publication
        $posts = Post::published()
            ->orderByDesc('published_at')
            ->get(['slug', 'updated_at', 'published_at']);

        // Fetch active merchant stores to help search engines discover them
        $stores = Store::where('status', 'active')
            ->latest('updated_at')
            ->take(500)
            ->get();

        $content = view('sitemap', [
            'baseUrl' => $baseUrl,
            'posts' => $posts,
            'stores' => $stores,
        ])->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
