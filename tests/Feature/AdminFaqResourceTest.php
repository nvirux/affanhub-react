<?php

use App\Filament\Resources\Faqs\Pages\ListFaqs;
use App\Models\Admin;
use App\Models\Faq;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = Admin::create([
        'name' => 'Super Admin',
        'email' => 'admin@affanhub.com',
        'password' => bcrypt('password'),
    ]);

    Filament::setCurrentPanel(Filament::getPanel('admin'));
    $this->actingAs($this->admin, 'admin');
});

test('admin can view faqs list in central admin', function () {
    $faq = Faq::create([
        'question' => 'How does customer float capital work?',
        'answer' => 'When a customer deposits funds, they become working capital.',
        'category' => 'capital',
        'audience' => 'all',
        'sort_order' => 1,
        'is_active' => true,
    ]);

    Livewire::test(ListFaqs::class)
        ->assertSuccessful()
        ->assertSee('How does customer float capital work?');
});
