<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->string('category')->default('general'); // capital, operations, pricing, branding, general
            $table->string('audience')->default('all'); // merchant, public, all
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Populate initial production FAQs immediately
        $faqs = [
            [
                'category' => 'capital',
                'audience' => 'all',
                'question' => 'How does the ₦0 startup capital (Customer Float) model work?',
                'answer' => "With AffanHub, you do not need to lock up hundreds of thousands of Naira in prepaid balance before starting your VTU business. Here is how your customers provide your operational capital:

1. A retail customer signs up on your storefront and deposits ₦5,000 via their assigned virtual bank account (Monnify/Paystack).
2. That ₦5,000 instantly credits your store's Main Operating Capital balance.
3. When the customer buys 1GB SME data (retail price: ₦260, wholesale cost: ₦225), AffanHub deducts ₦225 from your capital balance and credits ₦35 profit straight into your Profit Wallet.
4. Result: The customer's deposit funded the wholesale cost, and your profit is earned automatically without upfront cash out of your pocket.",
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'category' => 'capital',
                'audience' => 'merchant',
                'question' => 'How and when do I withdraw my profit to my personal bank account?',
                'answer' => 'Every Naira you make above your wholesale cost is kept completely separate in your Profit Wallet so it is never mixed up with customer working capital.

You can withdraw your earnings anytime by navigating to Finance & Wallet → Withdrawals. Simply enter the amount and select your saved Nigerian bank account. Withdrawals are processed seamlessly without arbitrary holding periods.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'category' => 'operations',
                'audience' => 'all',
                'question' => 'What happens if a telecom network experiences downtime or fails?',
                'answer' => "Unlike self-hosted VTU scripts where network downtime causes stuck money or manual complaints, AffanHub features Automated Failover & Instant Reversals:

If an upstream provider (MTN, Airtel, Glo, 9mobile) returns a failure or timeout after automatic rerouting attempts, the customer's wallet is instantly refunded in full. Your wholesale balance is never deducted, ensuring zero capital loss and zero manual support headaches for your store.",
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'category' => 'pricing',
                'audience' => 'merchant',
                'question' => 'How do I set my store retail prices and profit margins?',
                'answer' => "You have 100% control over what your retail customers pay. AffanHub pre-configures healthy default market retail prices, but you can customize them anytime:

• Data Bundles: Go to VTU Services → Data Plans and update the 'Your Store Price'.
• Airtime Discounts: Go to VTU Services → Airtime Discounts.
• Identity Slips: Go to Identity Services → Identity Slips Pricing.

To check your baseline purchase rates at any time, visit Settings → Wholesale Rates.",
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'category' => 'branding',
                'audience' => 'all',
                'question' => 'How do I connect my own custom domain (e.g. mystore.com)?',
                'answer' => "You can connect any domain you purchased from Namecheap, GoDaddy, Whogohost, or Google Domains:

1. In your merchant dashboard, go to Settings → Domains.
2. Add your domain name (e.g. vtu.mystore.com or mystore.ng).
3. In your domain registrar's DNS management, point a CNAME record to cname.affanhub.com.
4. AffanHub automatically verifies the connection and provisions a free, auto-renewing SSL certificate within minutes.",
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'category' => 'operations',
                'audience' => 'all',
                'question' => 'What is the difference between MTN SME, Corporate Gifting (CG), and Direct Gifting?',
                'answer' => 'Understanding data types helps you price competitively:

• MTN SME: The most popular, highly discounted data bundle in Nigeria. Valid for 30 days. Perfect for high-volume retail customers and students.
• MTN Corporate Gifting (CG): Premium enterprise-routed data with lightning-fast delivery and top-tier network uptime even when SME queues are congested.
• Direct Gifting: Official network gifting bundles that can share data directly and work on all devices without network restrictions.',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'category' => 'operations',
                'audience' => 'all',
                'question' => 'Why is AffanHub better than buying or hosting a standalone VTU script?',
                'answer' => 'With standalone scripts, you must pay for a VPS server every month, buy API keys, fix broken cPanel code when telecom providers change endpoints, and fix security vulnerabilities yourself.

With AffanHub, we manage 100% of the server infrastructure, provider API routing, database backups, security patches, and telecom uptime failovers. You focus solely on marketing your brand and keeping 100% of your profits.',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'category' => 'capital',
                'audience' => 'merchant',
                'question' => 'How do customer virtual bank account deposits work?',
                'answer' => 'Every customer who registers on your store is automatically issued a dedicated virtual bank account (via Monnify / Wema Bank / PalmPay). When they transfer funds from their bank mobile app or USSD, the deposit is confirmed in under 5 seconds, crediting their wallet and your store capital automatically.',
                'sort_order' => 8,
                'is_active' => true,
            ],
        ];

        $now = now();
        foreach ($faqs as &$faq) {
            $faq['created_at'] = $now;
            $faq['updated_at'] = $now;
        }

        DB::table('faqs')->insert($faqs);
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
