<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        Post::updateOrCreate(
            ['slug' => 'how-to-start-vtu-business-nigeria'],
            [
                'title' => 'How to Start a Profitable VTU & Digital Services Business in Nigeria (2026 Guide)',
                'category' => 'Guides',
                'excerpt' => 'A comprehensive beginner-friendly roadmap to building a recurring income stream selling airtime, cheap data bundles, electricity tokens, and identity verifications under your own brand.',
                'content' => '<h2>Why the VTU Industry is Booming</h2>
<p>In Nigeria, internet data and airtime are essential utilities. Millions of gigabytes are consumed daily for remote work, streaming, education, and social media. When you launch a digital services business, you are entering a market with non-stop daily repeat purchases.</p>

<h3>Step 1: Choose Your Core Product Offerings</h3>
<p>The highest margin products in the telecom and utility space include:</p>
<ul>
    <li><strong>SME & Corporate Data:</strong> MTN, Airtel, Glo, and 9mobile bundles at wholesale pricing.</li>
    <li><strong>Airtime Discounts:</strong> Instant top-ups with built-in reseller margins.</li>
    <li><strong>Electricity & Cable TV:</strong> Prepaid meter tokens (IKEDC, EKEDC, AEDC) and DStv/GOtv renewals.</li>
    <li><strong>Identity Services:</strong> NIN and BVN slip printing for local walk-in clients.</li>
</ul>

<h3>Step 2: Automate Fulfillment with AffanHub</h3>
<p>Rather than managing messy WhatsApp chats or manual bank transfers, AffanHub provides an automated storefront. Your customers receive dedicated virtual accounts, fund their wallets in seconds, and orders complete automatically in under 5 seconds.</p>

<h3>Step 3: Set Your Profit Margins Wisely</h3>
<p>Keep your prices competitive while protecting your margin. For example, if you purchase 1GB of MTN SME data at wholesale for ₦215, selling it at ₦260 nets you ₦45 clear profit per gigabyte. When 200 customers buy 3GB weekly, that adds up to steady passive cash flow.</p>',
                'cover_image' => null,
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'author_name' => 'AffanHub Team',
                'reading_time_minutes' => 4,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now()->subDays(2),
                'faqs' => [
                    [
                        'question' => 'How much startup capital do I need?',
                        'answer' => 'You can start completely free on AffanHub without paying any subscription fee. You only fund your working float when purchasing wholesale stock.',
                    ],
                    [
                        'question' => 'Do I need a registered CAC company to start?',
                        'answer' => 'No, you do not need CAC registration to launch your store on AffanHub. You can start immediately as an individual entrepreneur.',
                    ],
                    [
                        'question' => 'How fast do transactions deliver?',
                        'answer' => 'All airtime, data, and utility tokens are delivered automatically within 3 to 10 seconds through direct telecom gateways.',
                    ],
                ],
                'seo_title' => 'Start a VTU Business in Nigeria (2026 Roadmap) | AffanHub',
                'seo_description' => 'Learn how to start a high-margin data and VTU reseller business in Nigeria with automated storefronts, zero coding, and instant bank funding.',
            ]
        );

        Post::updateOrCreate(
            ['slug' => 'why-custom-domains-boost-reseller-sales'],
            [
                'title' => 'Why Connecting a Custom Domain Skyrockets Your Reseller Trust & Sales',
                'category' => 'Tutorials',
                'excerpt' => 'Discover how a professional custom domain (like yourbrand.com) builds instant buyer credibility and protects your pricing margins.',
                'content' => '<h2>First Impressions Matter in E-Commerce</h2>
<p>When customers buy telecom services online, trust is their #1 concern. A customer is 3x more likely to deposit money into a website with a clean, branded domain (like <code>swiftdata.ng</code>) than an untrusted generic link.</p>

<h3>The Power of 100% White-Labeling</h3>
<p>With AffanHub Pro and Enterprise tiers, your platform is completely white-labeled. Your customers never see the AffanHub logo or platform name. You build long-term brand equity that you own completely.</p>

<h3>Automatic SSL & Zero Hassle DNS</h3>
<p>AffanHub automatically provisions Cloudflare SSL certificates for your custom domain. Your site is fast, fully encrypted with HTTPS, and protected against downtime.</p>',
                'cover_image' => null,
                'youtube_url' => null,
                'author_name' => 'AffanHub Team',
                'reading_time_minutes' => 3,
                'is_featured' => false,
                'is_published' => true,
                'published_at' => now()->subDay(),
                'faqs' => [
                    [
                        'question' => 'Will my customers ever know I am using AffanHub?',
                        'answer' => 'Never. When you connect a custom domain, all customer receipts, invoices, emails, and web pages display strictly your brand name and logo.',
                    ],
                    [
                        'question' => 'How long does a custom domain take to activate?',
                        'answer' => 'With our 1-click Cloudflare connect and automatic verification, most custom domains go live in less than 5 minutes.',
                    ],
                ],
                'seo_title' => 'Why Custom Domains Boost Reseller Sales | AffanHub',
                'seo_description' => 'Learn why a white-label custom domain increases customer conversions and allows you to command premium prices.',
            ]
        );
    }
}
