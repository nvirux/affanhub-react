<?php

use App\Filament\Merchant\Pages\HelpFaq;
use App\Models\Owner;
use App\Models\Plan;
use App\Models\Store;
use Database\Seeders\ProductionBlogSeeder;
use Database\Seeders\ProductionFaqSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->owner = Owner::create([
        'name' => 'FAQ Merchant',
        'email' => 'faqmerchant@example.com',
        'password' => bcrypt('password'),
    ]);

    $this->store = Store::create([
        'name' => 'FAQ Store',
        'owner_id' => $this->owner->id,
    ]);

    Plan::firstOrCreate(
        ['slug' => 'starter'],
        [
            'name' => 'Starter',
            'price_monthly' => 0.00,
            'is_active' => true,
        ]
    );

    Filament::setCurrentPanel(Filament::getPanel('merchant'));
    $this->actingAs($this->owner, 'owner');
    Filament::setTenant($this->store);
    $this->seed(ProductionFaqSeeder::class);
    $this->seed(ProductionBlogSeeder::class);
});

test('merchant can access help and faq page with guides', function () {
    Livewire::test(HelpFaq::class)
        ->assertSuccessful()
        ->assertSee('Merchant Help')
        ->assertSee('Customer Float')
        ->assertSee('How and when do I withdraw my profit')
        ->assertSee('What happens if a telecom network experiences downtime')
        ->assertSee('/blog/start-vtu-business-zero-capital-nigeria');
});

test('merchant can filter faqs by category and search', function () {
    Livewire::test(HelpFaq::class)
        ->assertCount('faqs', 8)
        ->call('selectCategory', 'branding')
        ->assertSet('activeCategory', 'branding')
        ->assertSee('mystore.com')
        ->call('selectCategory', 'all')
        ->set('search', 'downtime')
        ->assertSee('network experiences downtime');
});
