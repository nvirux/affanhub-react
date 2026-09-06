<?php

namespace App\Filament\Merchant\Pages;

use App\Models\Domain;
use App\Models\Store;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Tenancy\RegisterTenant;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;

class RegisterStore extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register Store';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('subdomain')
                    ->label('Store Subdomain (e.g. "annur")')
                    ->required()
                    ->alphaDash()
                    ->notIn(config('tenancy.reserved_tenant_ids', []))
                    ->validationMessages([
                        'not_in' => 'This store URL is reserved by AffanHub and cannot be used.',
                    ])
                    ->rule(function () {
                        return function (string $attribute, $value, \Closure $fail) {
                            $baseDomain = parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';
                            $fullDomain = strtolower(trim($value)).'.'.$baseDomain;
                            if (Domain::where('domain', $fullDomain)->exists()) {
                                $fail('This store subdomain is already taken.');
                            }
                        };
                    }),
                TextInput::make('name')
                    ->label('Store Name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    protected function handleRegistration(array $data): Model
    {
        $store = Store::create([
            'name' => $data['name'],
            'owner_id' => auth()->id(),
        ]);

        $owner = auth()->user();

        if ($owner) {
            $owner->stores()->attach($store->id, ['role' => 'owner']);
        }

        // Automatically create the domain for this new store!
        // We use config('app.url') so it works dynamically in production (e.g., affanhub.com)
        $baseDomain = parse_url(config('app.url'), PHP_URL_HOST) ?? 'localhost';

        $store->domains()->create([
            'domain' => strtolower(trim($data['subdomain'])).'.'.$baseDomain,
            'is_primary' => true,
        ]);

        return $store;
    }
}
