<?php

namespace App\Filament\Merchant\Resources\StaffResource\Pages;

use App\Filament\Merchant\Resources\StaffResource;
use App\Models\Owner;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ListStaff extends ListRecords
{
    protected static string $resource = StaffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('inviteStaff')
                ->label('Invite Staff')
                ->icon('heroicon-o-paper-airplane')
                ->color('primary')
                ->modalHeading('Invite Staff Member')
                ->modalDescription('Invite an existing AffanHub account or add a new team member to help operate your store.')
                ->modalSubmitActionLabel('Send Invitation')
                ->form([
                    TextInput::make('email')
                        ->label('Staff Email Address')
                        ->email()
                        ->required()
                        ->placeholder('e.g. colleague@example.com')
                        ->helperText('If this user already has an AffanHub account, they will simply be granted access without creating a duplicate account.'),

                    TextInput::make('name')
                        ->label('Full Name')
                        ->required()
                        ->placeholder('e.g. Jane Doe'),

                    TextInput::make('phone')
                        ->label('Phone Number')
                        ->tel()
                        ->placeholder('e.g. 08012345678'),

                    Select::make('role')
                        ->label('Store Role')
                        ->options([
                            'manager' => 'Manager (Full operational access to catalog, pricing, and orders)',
                            'staff' => 'Staff (Standard operational and order support)',
                        ])
                        ->default('staff')
                        ->required(),

                    TextInput::make('password')
                        ->label('Temporary Password (Optional)')
                        ->password()
                        ->revealable()
                        ->placeholder('Leave blank to auto-generate')
                        ->helperText('Only used when creating a new user account. Existing accounts keep their current password.'),
                ])
                ->action(function (array $data) {
                    $tenant = Filament::getTenant();

                    if (! $tenant) {
                        return;
                    }

                    // Check staff quota
                    $staffLimit = $tenant->getFeatureLimit('staff_limit');
                    $currentStaffCount = $tenant->members()->count();

                    if ($currentStaffCount >= $staffLimit) {
                        Notification::make()
                            ->title('Staff Limit Reached')
                            ->body("Your current plan allows a maximum of {$staffLimit} staff members. Please upgrade your subscription on the Billing page.")
                            ->danger()
                            ->persistent()
                            ->send();

                        return;
                    }

                    $email = strtolower(trim($data['email']));
                    $role = $data['role'] ?? 'staff';

                    // Check if owner already exists
                    $existingOwner = Owner::where('email', $email)->first();

                    if ($existingOwner) {
                        // Check if already a member of this store
                        if ($tenant->members()->where('owner_id', $existingOwner->id)->exists()) {
                            Notification::make()
                                ->title('Already a Staff Member')
                                ->body("{$existingOwner->name} ({$email}) is already a staff member of your store.")
                                ->warning()
                                ->send();

                            return;
                        }

                        // Attach existing owner to this store
                        $tenant->members()->attach($existingOwner->id, ['role' => $role]);

                        Notification::make()
                            ->title('Staff Member Invited')
                            ->body("{$existingOwner->name} has been successfully added to your store as a {$role}!")
                            ->success()
                            ->send();

                        return;
                    }

                    // Create new owner account
                    $generatedPassword = ! empty($data['password']) ? $data['password'] : Str::random(10);

                    $newOwner = Owner::create([
                        'name' => $data['name'],
                        'email' => $email,
                        'phone' => $data['phone'] ?? null,
                        'password' => Hash::make($generatedPassword),
                        'email_verified_at' => now(),
                        'max_stores' => 3,
                    ]);

                    $tenant->members()->attach($newOwner->id, ['role' => $role]);

                    Notification::make()
                        ->title('Staff Account Created & Invited')
                        ->body("{$newOwner->name} was added as {$role}! Temporary Password: {$generatedPassword}")
                        ->success()
                        ->persistent()
                        ->send();
                }),
        ];
    }
}
