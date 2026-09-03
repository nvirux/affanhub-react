<?php

namespace App\Filament\Merchant\Resources\StaffResource\Pages;

use App\Filament\Merchant\Resources\StaffResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;

class EditStaff extends EditRecord
{
    protected static string $resource = StaffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Get the pivot role for the current store
        $tenant = Filament::getTenant();
        $pivot = $this->record->stores()->where('store_id', $tenant->id)->first()?->pivot;
        if ($pivot) {
            $data['role'] = $pivot->role;
        } else {
            $data['role'] = 'staff';
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $role = $data['role'] ?? 'staff';
        unset($data['role']);

        $record->update($data);

        // Update the pivot table role for this tenant store
        $tenant = Filament::getTenant();
        $record->stores()->updateExistingPivot($tenant->id, ['role' => $role]);

        return $record;
    }
}
