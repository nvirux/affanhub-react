<?php

namespace App\Filament\Merchant\Resources\StaffResource\Pages;

use App\Filament\Merchant\Resources\StaffResource;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditStaff extends EditRecord
{
    protected static string $resource = StaffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('remove')
                ->label('Remove from Store')
                ->icon('heroicon-o-user-minus')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Remove Staff Member')
                ->modalDescription('Are you sure you want to remove this staff member from this store? Their user account will remain intact, but they will lose access to this store.')
                ->action(function () {
                    $tenant = Filament::getTenant();
                    if ($tenant) {
                        $tenant->members()->detach($this->record->id);
                    }

                    Notification::make()
                        ->title('Staff member removed from store')
                        ->success()
                        ->send();

                    $this->redirect(StaffResource::getUrl('index'));
                })
                ->hidden(fn () => $this->record->id === auth()->id() || $this->record->stores()->where('store_id', Filament::getTenant()?->id)->first()?->pivot?->role === 'owner'),
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
        $role = $data['role'] ?? null;
        unset($data['role']);

        $record->update($data);

        // Update the pivot table role for this tenant store (only if not owner)
        $tenant = Filament::getTenant();
        if ($tenant) {
            $currentRole = $record->stores()->where('store_id', $tenant->id)->first()?->pivot?->role;
            if ($currentRole !== 'owner' && $role) {
                $record->stores()->updateExistingPivot($tenant->id, ['role' => $role]);
            }
        }

        return $record;
    }
}
