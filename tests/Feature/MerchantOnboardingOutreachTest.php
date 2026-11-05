<?php

use App\Filament\Resources\Owners\Pages\ListOwners;
use App\Filament\Resources\Stores\Pages\ListStores;
use App\Mail\MerchantDirectMessageMail;
use App\Mail\MerchantWelcomeMail;
use App\Models\Admin;
use App\Models\Owner;
use App\Models\Store;
use App\Services\Communication\MerchantOutreachService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('MerchantOutreachService formats local and international numbers accurately for WhatsApp', function () {
    expect(MerchantOutreachService::formatPhoneForWhatsApp('08031234567'))->toBe('2348031234567')
        ->and(MerchantOutreachService::formatPhoneForWhatsApp('+2348031234567'))->toBe('2348031234567')
        ->and(MerchantOutreachService::formatPhoneForWhatsApp('2348031234567'))->toBe('2348031234567')
        ->and(MerchantOutreachService::formatPhoneForWhatsApp('090 1234 5678'))->toBe('2349012345678')
        ->and(MerchantOutreachService::formatPhoneForWhatsApp(null))->toBeNull();

    $url = MerchantOutreachService::buildWhatsAppUrl('08012345678', 'Hello Merchant');
    expect($url)->toBe('https://wa.me/2348012345678?text=Hello%20Merchant');
});

test('MerchantWelcomeMail renders with correct subject and merchant details', function () {
    $owner = Owner::create([
        'name' => 'Alhaji Musa',
        'email' => 'musa@example.com',
        'password' => bcrypt('password'),
    ]);

    $mailable = new MerchantWelcomeMail($owner);
    $mailable->assertHasSubject('Welcome to AffanHub! 🚀 Launch Your Branded VTU Platform');
    $mailable->assertSeeInHtml('Alhaji Musa');
    $mailable->assertSeeInHtml('Open Merchant Dashboard');
});

test('MerchantDirectMessageMail sends custom email with admin text', function () {
    Mail::fake();

    $owner = Owner::create([
        'name' => 'Chioma Adebayo',
        'email' => 'chioma@example.com',
        'password' => bcrypt('password'),
    ]);

    $subject = 'Special Wholesale Discount Available';
    $body = "Hi Chioma,\nWe have funded ₦500 bonus to your wallet to try out our platform.";

    Mail::to($owner->email)->send(new MerchantDirectMessageMail($owner, $subject, $body));

    Mail::assertSent(MerchantDirectMessageMail::class, function ($mail) use ($subject) {
        return $mail->hasSubject($subject);
    });
});

test('ListOwners needs_followup tab displays stalled accounts with no stores', function () {
    $admin = Admin::create([
        'name' => 'Admin User',
        'email' => 'admin_outreach@example.com',
        'password' => bcrypt('password'),
    ]);

    // Active store owner
    $activeOwner = Owner::create([
        'name' => 'Active Merchant',
        'email' => 'active@example.com',
        'password' => bcrypt('password'),
    ]);
    Store::create([
        'name' => 'Active Store',
        'public_id' => 'str_active',
        'owner_id' => $activeOwner->id,
        'status' => 'active',
    ]);

    // Stalled merchant (signed up > 24 hours ago, no store)
    $stalledOwner = Owner::create([
        'name' => 'Stalled Merchant',
        'email' => 'stalled@example.com',
        'password' => bcrypt('password'),
        'created_at' => now()->subHours(36),
    ]);

    $this->actingAs($admin, 'admin');

    Livewire::test(ListOwners::class)
        ->assertSuccessful()
        ->set('activeTab', 'needs_followup')
        ->assertSee('Stalled Merchant')
        ->assertDontSee('Active Merchant');
});

test('ListStores unfunded tab accurately detects stores with zero wholesale balance', function () {
    $admin = Admin::create([
        'name' => 'Admin User 2',
        'email' => 'admin_outreach2@example.com',
        'password' => bcrypt('password'),
    ]);

    $owner = Owner::create([
        'name' => 'Owner 2',
        'email' => 'owner2@example.com',
        'password' => bcrypt('password'),
    ]);

    // Unfunded store (0 balance)
    $unfundedStore = Store::create([
        'name' => 'Unfunded Store',
        'public_id' => 'str_unfunded',
        'owner_id' => $owner->id,
        'status' => 'active',
    ]);

    // Funded store
    $fundedStore = Store::create([
        'name' => 'Funded Store',
        'public_id' => 'str_funded',
        'owner_id' => $owner->id,
        'status' => 'active',
    ]);
    $fundedStore->mainWallet()->update(['balance' => 20000.00]);

    $this->actingAs($admin, 'admin');

    Livewire::test(ListStores::class)
        ->assertSuccessful()
        ->set('activeTab', 'unfunded')
        ->assertSee('Unfunded Store')
        ->assertDontSee('Funded Store');
});
