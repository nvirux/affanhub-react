<?php

namespace App\Filament\Merchant\Resources\StaffResource\Pages;

use App\Filament\Merchant\Resources\StaffResource;
use App\Mail\StaffInvitationMail;
use App\Models\Owner;
use App\Models\StaffInvitation;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ListStaff extends ListRecords
{
    protected static string $resource = StaffResource::class;

    public function mount(): void
    {
        $tenant = Filament::getTenant();
        if (auth()->id() !== $tenant?->owner_id) {
            abort(403, 'Unauthorized. Only the store owner can access staff management.');
        }

        parent::mount();
    }

    protected function getHeaderActions(): array
    {
        $isOwner = fn () => auth()->id() === Filament::getTenant()?->owner_id;

        return [
            Action::make('pendingInvitations')
                ->visible($isOwner)
                ->label(function () {
                    $tenant = Filament::getTenant();
                    $count = $tenant ? $tenant->staffInvitations()->count() : 0;

                    return $count > 0 ? "Pending Invites ({$count})" : 'Pending Invites';
                })
                ->icon('heroicon-o-clock')
                ->color('gray')
                ->badge(function () {
                    $tenant = Filament::getTenant();

                    return $tenant ? ($tenant->staffInvitations()->count() ?: null) : null;
                })
                ->modalHeading('Pending Staff Invitations')
                ->modalDescription('View and manage team invitations waiting for acceptance.')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close')
                ->modalContent(function () {
                    $tenant = Filament::getTenant();

                    return view('filament.merchant.pages.pending-invitations', [
                        'invitations' => $tenant ? $tenant->staffInvitations()->latest()->get() : collect(),
                    ]);
                }),

            Action::make('inviteStaff')
                ->visible($isOwner)
                ->label('Invite Staff')
                ->icon('heroicon-o-paper-airplane')
                ->color('primary')
                ->modalHeading('Invite Staff Member')
                ->modalDescription('Enter the email address of the person you want to invite. They will receive an invitation email to set up their account and join your team.')
                ->modalSubmitActionLabel('Send Invitation Email')
                ->form([
                    TextInput::make('email')
                        ->label('Staff Email Address')
                        ->email()
                        ->required()
                        ->placeholder('e.g. colleague@example.com')
                        ->helperText('The invitee will receive an email invitation to accept. If they are already on AffanHub, they can simply log in.'),

                    Select::make('role')
                        ->label('Store Role')
                        ->options([
                            'manager' => 'Manager (Operational access to catalog, pricing, and orders)',
                            'staff' => 'Staff (Standard day-to-day order support and operations)',
                        ])
                        ->default('staff')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $tenant = Filament::getTenant();

                    if (! $tenant) {
                        return;
                    }

                    // 1. Check staff limit quota
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

                    // 2. Check if already an active member of this store
                    $existingOwner = Owner::where('email', $email)->first();
                    if ($existingOwner && $tenant->members()->where('owner_id', $existingOwner->id)->exists()) {
                        Notification::make()
                            ->title('Already a Staff Member')
                            ->body("{$existingOwner->name} ({$email}) is already an active member of your store.")
                            ->warning()
                            ->send();

                        return;
                    }

                    // 3. Create or refresh StaffInvitation
                    $token = StaffInvitation::generateToken();
                    $invitation = StaffInvitation::updateOrCreate(
                        [
                            'store_id' => $tenant->id,
                            'email' => $email,
                        ],
                        [
                            'role' => $role,
                            'token' => $token,
                            'invited_by' => auth()->id(),
                            'expires_at' => now()->addDays(7),
                        ]
                    );

                    // 4. Send email
                    try {
                        Mail::to($email)->send(new StaffInvitationMail($invitation));

                        Notification::make()
                            ->title('Invitation Sent!')
                            ->body("An invitation email has been sent to {$email} with instructions to join as {$role}.")
                            ->success()
                            ->send();
                    } catch (\Throwable $e) {
                        Log::error('Staff Invitation Email Error: '.$e->getMessage(), ['exception' => $e]);

                        Notification::make()
                            ->title('Invitation Saved')
                            ->body("Invitation created for {$email}. (Email delivery note: check mailer configuration or copy link from Pending Invites).")
                            ->warning()
                            ->send();
                    }
                }),
        ];
    }

    /**
     * Resend an invitation email with a refreshed token and expiry.
     */
    public function resendInvitation(int $id): void
    {
        $tenant = Filament::getTenant();
        $invitation = $tenant?->staffInvitations()->find($id);

        if (! $invitation) {
            Notification::make()->title('Invitation not found')->danger()->send();

            return;
        }

        $invitation->update([
            'token' => StaffInvitation::generateToken(),
            'expires_at' => now()->addDays(7),
        ]);

        try {
            Mail::to($invitation->email)->send(new StaffInvitationMail($invitation));

            Notification::make()
                ->title('Invitation Resent')
                ->body("A fresh invitation link was emailed to {$invitation->email}.")
                ->success()
                ->send();
        } catch (\Throwable $e) {
            Log::error('Staff Invitation Resend Error: '.$e->getMessage());

            Notification::make()
                ->title('Resend Failed')
                ->body('Unable to send email. Check mail server logs.')
                ->danger()
                ->send();
        }
    }

    /**
     * Revoke and cancel a pending invitation.
     */
    public function revokeInvitation(int $id): void
    {
        $tenant = Filament::getTenant();
        $invitation = $tenant?->staffInvitations()->find($id);

        if ($invitation) {
            $email = $invitation->email;
            $invitation->delete();

            Notification::make()
                ->title('Invitation Revoked')
                ->body("The invitation for {$email} has been cancelled.")
                ->success()
                ->send();
        }
    }
}
