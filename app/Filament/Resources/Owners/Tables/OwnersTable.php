<?php

namespace App\Filament\Resources\Owners\Tables;

use App\Mail\MerchantDirectMessageMail;
use App\Models\ActivityLog;
use App\Models\Owner;
use App\Services\Communication\MerchantOutreachService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OwnersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label('')
                    ->circular()
                    ->defaultImageUrl(fn (Owner $record): string => 'https://ui-avatars.com/api/?name='.urlencode($record->name).'&background=f1f5f9&color=334155&bold=true'),

                TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn (Owner $record): ?string => $record->phone),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Email address copied')
                    ->icon('heroicon-m-envelope'),

                TextColumn::make('account_type')
                    ->label('Type')
                    ->badge()
                    ->state(fn (Owner $record): string => $record->ownedStores()->exists()
                        ? 'Store Owner'
                        : ($record->stores()->exists() ? 'Store Staff' : 'Registered User'))
                    ->color(fn (string $state): string => match ($state) {
                        'Store Owner' => 'success',
                        'Store Staff' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('stores.name')
                    ->label('Stores Assigned')
                    ->badge()
                    ->color('primary')
                    ->limitList(2)
                    ->expandableLimitedList()
                    ->searchable()
                    ->placeholder('None'),

                TextColumn::make('owned_stores_count')
                    ->label('Stores Owned')
                    ->counts('ownedStores')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                IconColumn::make('email_verified_at')
                    ->label('Verified')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->sortable(),

                IconColumn::make('app_authentication_secret')
                    ->label('2FA')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                TernaryFilter::make('email_verified_at')
                    ->label('Email Verification')
                    ->nullable(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()
                        ->modalHeading(fn (Owner $record): string => "Account Details: {$record->name}")
                        ->form([
                            TextInput::make('name')
                                ->label('Full Name'),
                            TextInput::make('email')
                                ->label('Email Address'),
                            TextInput::make('phone')
                                ->label('Phone Number')
                                ->placeholder('No phone provided'),
                            TextInput::make('account_type')
                                ->label('Account Type')
                                ->formatStateUsing(fn ($record): string => $record->ownedStores()->exists() ? 'Store Owner' : ($record->stores()->exists() ? 'Store Staff' : 'Registered User')),
                            TextInput::make('max_stores')
                                ->label('Store Creation Quota')
                                ->formatStateUsing(fn ($record): string => ($record->max_stores ?? 3).' Stores'),
                            TextInput::make('email_verified_at')
                                ->label('Email Verified Date')
                                ->placeholder('Unverified'),
                            TextInput::make('created_at')
                                ->label('Registration Date'),
                            TextInput::make('owned_stores_list')
                                ->label('Owned Stores')
                                ->formatStateUsing(fn ($record): string => $record->ownedStores->pluck('name')->join(', ') ?: 'None')
                                ->columnSpanFull(),
                            TextInput::make('member_stores_list')
                                ->label('Staff / Assigned Stores')
                                ->formatStateUsing(fn ($record): string => $record->stores->where('owner_id', '!=', $record->id)->pluck('name')->join(', ') ?: 'None')
                                ->columnSpanFull(),
                        ]),

                    EditAction::make(),

                    Action::make('whatsapp_merchant')
                        ->label('Chat on WhatsApp')
                        ->icon('heroicon-o-chat-bubble-left-right')
                        ->color('success')
                        ->modalHeading(fn (Owner $record): string => "WhatsApp Outreach: {$record->name}")
                        ->modalDescription('Choose a pre-filled outreach template or customize your message before opening WhatsApp.')
                        ->modalSubmitActionLabel('Open WhatsApp Chat ↗')
                        ->form(function (Owner $record) {
                            $templates = MerchantOutreachService::getTemplates($record->name, $record->ownedStores->first()?->name);
                            $options = collect($templates)->mapWithKeys(fn ($item, $key) => [$key => $item['label']])->toArray();
                            $options['custom'] = '✏️ Custom Message';

                            return [
                                TextInput::make('phone')
                                    ->label('WhatsApp Phone Number')
                                    ->default(fn () => $record->phone)
                                    ->required()
                                    ->helperText('Formatted automatically for WhatsApp (+234...).'),

                                Select::make('template')
                                    ->label('Choose Message Template')
                                    ->options($options)
                                    ->default($record->ownedStores()->exists() ? 'unfunded_capital' : 'welcome_launch')
                                    ->live()
                                    ->afterStateUpdated(function ($state, $set) use ($templates) {
                                        if (isset($templates[$state])) {
                                            $set('message', $templates[$state]['message']);
                                        }
                                    }),

                                Textarea::make('message')
                                    ->label('Message Content')
                                    ->default(function () use ($record, $templates) {
                                        $defKey = $record->ownedStores()->exists() ? 'unfunded_capital' : 'welcome_launch';

                                        return $templates[$defKey]['message'] ?? '';
                                    })
                                    ->rows(4)
                                    ->required(),
                            ];
                        })
                        ->action(function (array $data) {
                            $url = MerchantOutreachService::buildWhatsAppUrl($data['phone'], $data['message']);

                            return redirect()->away($url);
                        }),

                    Action::make('send_direct_email')
                        ->label('Send Direct Email')
                        ->icon('heroicon-o-envelope')
                        ->color('info')
                        ->modalHeading(fn (Owner $record): string => "Send Email to {$record->name}")
                        ->modalSubmitActionLabel('Send Email Now')
                        ->form([
                            TextInput::make('subject')
                                ->label('Email Subject')
                                ->default('Checking in from AffanHub Support')
                                ->required(),
                            Textarea::make('body')
                                ->label('Email Message Body')
                                ->placeholder('Write your message to the merchant here...')
                                ->rows(6)
                                ->required(),
                        ])
                        ->action(function (Owner $record, array $data) {
                            try {
                                Mail::to($record->email)->send(
                                    new MerchantDirectMessageMail($record, $data['subject'], $data['body'])
                                );

                                ActivityLog::create([
                                    'tenant_id' => null,
                                    'causer_type' => Auth::user() ? get_class(Auth::user()) : null,
                                    'causer_id' => Auth::id(),
                                    'event' => 'admin_sent_merchant_email',
                                    'description' => "Admin sent direct email to {$record->name} ({$record->email}): \"{$data['subject']}\"",
                                    'properties' => [
                                        'owner_id' => $record->id,
                                        'email' => $record->email,
                                        'subject' => $data['subject'],
                                    ],
                                ]);

                                Notification::make()
                                    ->title("Email sent to {$record->email} successfully.")
                                    ->success()
                                    ->send();
                            } catch (\Throwable $e) {
                                Notification::make()
                                    ->title('Failed to send email: '.$e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),

                    Action::make('impersonate_store')
                        ->label('Impersonate Store')
                        ->icon('heroicon-o-arrow-right-end-on-rectangle')
                        ->color('success')
                        ->visible(fn (Owner $record): bool => $record->stores()->exists())
                        ->requiresConfirmation()
                        ->modalHeading(fn (Owner $record): string => "Impersonate {$record->name}'s Store")
                        ->modalDescription('You will be securely logged in to the merchant panel as this merchant. You can exit back to Super Admin at any time.')
                        ->form([
                            Toggle::make('anonymous')
                                ->label('Anonymous / Silent Mode')
                                ->helperText('If enabled, this session will not be logged in activity logs.')
                                ->default(false),
                        ])
                        ->action(function (Owner $record, array $data) {
                            $store = $record->ownedStores()->first() ?? $record->stores()->first();
                            if (! $store) {
                                Notification::make()
                                    ->title('No store found for this account.')
                                    ->danger()
                                    ->send();

                                return;
                            }

                            $adminUser = Auth::guard('admin')->user() ?? Auth::user();
                            if (! $adminUser) {
                                Notification::make()
                                    ->title('Unauthorized: Super Admin session required.')
                                    ->danger()
                                    ->send();

                                return;
                            }

                            $isAnonymous = (bool) ($data['anonymous'] ?? false);

                            $token = Str::random(40);
                            Cache::put("impersonate_token_{$token}", [
                                'admin_id' => $adminUser->id,
                                'admin_name' => $adminUser->name ?? 'Super Admin',
                                'owner_id' => $record->id,
                                'store_id' => $store->id,
                                'store_name' => $store->name,
                                'anonymous' => $isAnonymous,
                                'created_at' => now()->timestamp,
                            ], now()->addSeconds(60));

                            if (! $isAnonymous) {
                                ActivityLog::create([
                                    'tenant_id' => $store->id,
                                    'causer_type' => get_class($adminUser),
                                    'causer_id' => $adminUser->id,
                                    'event' => 'admin_impersonate_store_start',
                                    'description' => sprintf('Super Admin %s started impersonating store %s (Owner: %s)', $adminUser->name ?? 'Admin', $store->name, $record->name),
                                    'properties' => [
                                        'store_id' => $store->id,
                                        'store_name' => $store->name,
                                        'owner_id' => $record->id,
                                    ],
                                ]);
                            }

                            $scheme = request()->getScheme();
                            $host = request()->getHost();
                            $cleanHost = preg_replace('/^(admin|merchant)\./', '', $host);
                            $port = request()->getPort();
                            $portStr = ($port && ! in_array($port, [80, 443])) ? ":{$port}" : '';

                            $url = "{$scheme}://merchant.{$cleanHost}{$portStr}/impersonate/consume?token={$token}&tenant={$store->public_id}";

                            return redirect()->away($url);
                        }),

                    Action::make('verify_email_manually')
                        ->label('Verify Email Manually')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->visible(fn (Owner $record): bool => is_null($record->email_verified_at))
                        ->requiresConfirmation()
                        ->modalHeading('Manually Verify Email')
                        ->modalDescription(fn (Owner $record): string => "Are you sure you want to mark {$record->email} as email verified?")
                        ->action(function (Owner $record) {
                            $record->update(['email_verified_at' => now()]);
                            Notification::make()
                                ->title('Email successfully marked as verified.')
                                ->success()
                                ->send();
                        }),

                    Action::make('send_email_otp')
                        ->label('Send Verification OTP')
                        ->icon('heroicon-o-paper-airplane')
                        ->color('info')
                        ->visible(fn (Owner $record): bool => is_null($record->email_verified_at))
                        ->requiresConfirmation()
                        ->modalHeading('Send Verification OTP')
                        ->modalDescription(fn (Owner $record): string => "Send 6-digit email verification OTP to {$record->email}?")
                        ->action(function (Owner $record) {
                            $record->sendEmailVerificationNotification();
                            Notification::make()
                                ->title('Verification OTP sent to '.$record->email)
                                ->success()
                                ->send();
                        }),

                    Action::make('adjust_quota')
                        ->label('Adjust Store Quota')
                        ->icon('heroicon-o-adjustments-horizontal')
                        ->color('warning')
                        ->form([
                            TextInput::make('max_stores')
                                ->label('Store Creation Quota')
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(100)
                                ->default(fn (Owner $record): int => $record->max_stores ?? 3)
                                ->required(),
                        ])
                        ->action(function (Owner $record, array $data) {
                            $record->update(['max_stores' => $data['max_stores']]);
                            Notification::make()
                                ->title("Max store quota updated to {$data['max_stores']}.")
                                ->success()
                                ->send();
                        }),

                    DeleteAction::make(),
                ])
                    ->label('More Actions')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->color('gray')
                    ->tooltip('Account Actions'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
