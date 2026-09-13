<?php

namespace App\Filament\Merchant\Resources\StaffResource\Pages;

use App\Filament\Merchant\Resources\StaffResource;
use App\Models\Owner;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateStaff extends CreateRecord
{
    protected static string $resource = StaffResource::class;

    protected static ?string $title = 'Invite Staff Member';

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->label('Send Invitation');
    }

    protected function beforeCreate(): void
    {
        $tenant = Filament::getTenant();
        $staffLimit = $tenant->getFeatureLimit('staff_limit');

        $currentStaffCount = $tenant->members()->count();

        if ($currentStaffCount >= $staffLimit) {
            Notification::make()
                ->title('Staff Limit Reached')
                ->body("Your current plan allows a maximum of {$staffLimit} staff members. Please upgrade your subscription on the Billing page.")
                ->danger()
                ->persistent()
                ->send();

            $this->halt();
        }
    }

    protected function handleRecordCreation(array $data): Model
    {
        $role = $data['role'] ?? 'staff';
        unset($data['role']);

        $email = strtolower(trim($data['email']));
        $tenant = Filament::getTenant();

        $existing = Owner::where('email', $email)->first();

        if ($existing) {
            if ($tenant->members()->where('owner_id', $existing->id)->exists()) {
                Notification::make()
                    ->title('Already a Staff Member')
                    ->body('This user is already a member of this store.')
                    ->warning()
                    ->send();

                $this->halt();
            }

            $tenant->members()->attach($existing->id, ['role' => $role]);

            Notification::make()
                ->title('Staff Member Invited')
                ->body("{$existing->name} has been added to your store as a {$role}!")
                ->success()
                ->send();

            return $existing;
        }

        if (empty($data['password'])) {
            $data['password'] = Hash::make(Str::random(10));
        }

        $data['max_stores'] = 3;
        $data['email_verified_at'] = now();

        $record = Owner::create($data);
        $tenant->members()->attach($record->id, ['role' => $role]);

        return $record;
    }
}
