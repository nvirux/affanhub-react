<?php

namespace App\Filament\Merchant\Resources\StaffResource\Pages;

use App\Filament\Merchant\Resources\StaffResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class CreateStaff extends CreateRecord
{
    protected static string $resource = StaffResource::class;

    protected function beforeCreate(): void
    {
        $tenant = Filament::getTenant();
        $staffLimit = $tenant->getFeatureLimit('staff_limit');
        
        // Count store members (excluding the owner role if we want, or total accounts)
        // Let's count total store members
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

        // Create the Owner record
        $record = \App\Models\Owner::create($data);

        // Associate with the current store tenant
        $tenant = Filament::getTenant();
        $tenant->members()->attach($record->id, ['role' => $role]);

        return $record;
    }
}
