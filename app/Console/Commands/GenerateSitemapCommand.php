<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSitemapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate {--url= : The base URL to use for sitemap links}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a static sitemap.xml file in the public directory';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $baseUrl = rtrim((string) ($this->option('url') ?: config('app.url', 'https://affanhub.com')), '/');

        $this->info("Generating sitemap with base URL: {$baseUrl}...");

        $posts = Post::published()
            ->orderByDesc('published_at')
            ->get(['slug', 'updated_at', 'published_at']);

        $xml = view('sitemap', [
            'baseUrl' => $baseUrl,
            'posts' => $posts,
        ])->render();

        $path = public_path('sitemap.xml');
        File::put($path, $xml);

        $this->info("Sitemap successfully generated at: {$path}");
        $this->info("Included pages: Home (/), Blog (/blog), Privacy Policy (/privacy-policy), and {$posts->count()} published blog posts.");

        return Command::SUCCESS;
    }
}
