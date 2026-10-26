<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreModificationService extends Model
{
    protected $table = 'store_modification_services';

    protected $fillable = [
        'store_id',
        'service_id',
        'name',
        'slug',
        'description',
        'price',
        'processing_time',
        'required_documents',
        'fields',
        'instructions',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'required_documents' => 'array',
        'fields' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(ModificationRequest::class, 'store_modification_service_id');
    }

    public function isNin(): bool
    {
        return $this->service?->key === 'nin_modification' || str_starts_with($this->slug, 'nin_');
    }

    public function isBvn(): bool
    {
        return $this->service?->key === 'bvn_modification' || str_starts_with($this->slug, 'bvn_');
    }

    /**
     * Get documents normalized as an array of ['name' => ..., 'is_required' => bool, 'description' => ...]
     */
    public function getNormalizedDocuments(): array
    {
        $docs = $this->required_documents ?? [];
        $normalized = [];

        foreach ($docs as $doc) {
            if (is_string($doc)) {
                $normalized[] = [
                    'name' => $doc,
                    'is_required' => true,
                    'description' => null,
                ];
            } elseif (is_array($doc) && ! empty($doc['name'])) {
                $normalized[] = [
                    'name' => $doc['name'],
                    'is_required' => (bool) ($doc['is_required'] ?? true),
                    'description' => $doc['description'] ?? null,
                ];
            }
        }

        return $normalized;
    }

    /**
     * Get fields normalized as an array of ['label' => ..., 'placeholder' => ..., 'type' => ..., 'is_required' => bool]
     */
    public function getNormalizedFields(): array
    {
        $fields = $this->fields ?? [];
        $normalized = [];

        foreach ($fields as $field) {
            if (is_array($field) && ! empty($field['label'])) {
                $normalized[] = [
                    'label' => $field['label'],
                    'placeholder' => $field['placeholder'] ?? '',
                    'type' => $field['type'] ?? 'text',
                    'is_required' => (bool) ($field['is_required'] ?? true),
                ];
            }
        }

        return $normalized;
    }

    /**
     * Auto-seed default modification services for a given store.
     */
    public static function seedDefaultsForStore(Store $store): void
    {
        $ninService = Service::where('key', 'nin_modification')->first();
        $bvnService = Service::where('key', 'bvn_modification')->first();

        $presets = [
            // NIN Presets
            [
                'service_id' => $ninService?->id,
                'name' => 'Change of Name',
                'slug' => 'nin_change_of_name',
                'description' => 'Correction or update of First Name, Middle Name, or Surname on NIMC database.',
                'price' => 3500.00,
                'processing_time' => '24 - 48 Hours',
                'fields' => [
                    ['label' => 'Previous Full Name', 'placeholder' => 'Enter previous name on your NIN', 'type' => 'text', 'is_required' => true],
                    ['label' => 'New Full Name', 'placeholder' => 'Enter new legal name to update', 'type' => 'text', 'is_required' => true],
                    ['label' => 'Reason for Change', 'placeholder' => 'e.g. Marriage, Spelling correction, Chieftaincy', 'type' => 'text', 'is_required' => false],
                ],
                'required_documents' => [
                    ['name' => 'Court Affidavit', 'is_required' => true, 'description' => 'Signed & stamped by High Court'],
                    ['name' => 'Newspaper Publication', 'is_required' => true, 'description' => 'National daily newspaper cut-out'],
                    ['name' => 'Current NIN Slip', 'is_required' => true, 'description' => 'Clear photo of current NIN'],
                    ['name' => 'Marriage Certificate', 'is_required' => false, 'description' => 'Required if change is due to marriage'],
                ],
                'instructions' => 'Ensure court affidavit is signed and dated by high court. Name on newspaper publication must match new name requested.',
                'sort_order' => 1,
            ],
            [
                'service_id' => $ninService?->id,
                'name' => 'Date of Birth (DOB) Correction',
                'slug' => 'nin_dob_correction',
                'description' => 'Correction of birth date on national identity database with NPC or court verification.',
                'price' => 4500.00,
                'processing_time' => '3 - 5 Days',
                'fields' => [
                    ['label' => 'Current Wrong Date of Birth on NIN', 'placeholder' => 'YYYY-MM-DD', 'type' => 'date', 'is_required' => true],
                    ['label' => 'Correct Date of Birth', 'placeholder' => 'YYYY-MM-DD', 'type' => 'date', 'is_required' => true],
                ],
                'required_documents' => [
                    ['name' => 'NPC Birth Certificate / Attestation', 'is_required' => true, 'description' => 'National Population Commission birth attestation'],
                    ['name' => 'Court Affidavit', 'is_required' => true, 'description' => 'Declaration of age affidavit'],
                    ['name' => 'Current NIN Slip', 'is_required' => true, 'description' => 'Clear photo of current NIN'],
                    ['name' => 'Passport Photograph', 'is_required' => false, 'description' => 'Recent passport photo on white background'],
                ],
                'instructions' => 'National Population Commission (NPC) birth attestation or age declaration affidavit required.',
                'sort_order' => 2,
            ],
            [
                'service_id' => $ninService?->id,
                'name' => 'Phone Number Update',
                'slug' => 'nin_phone_update',
                'description' => 'Link a new active phone number to your NIN for receiving NIMC OTPs.',
                'price' => 2500.00,
                'processing_time' => '24 - 48 Hours',
                'fields' => [
                    ['label' => 'Old Phone Number on NIN', 'placeholder' => 'Previous phone (if known)', 'type' => 'text', 'is_required' => false],
                    ['label' => 'New Active Phone Number', 'placeholder' => '080XXXXXXXX (Registered in your name)', 'type' => 'text', 'is_required' => true],
                ],
                'required_documents' => [
                    ['name' => 'Court Affidavit / Police Extract', 'is_required' => true, 'description' => 'Loss of SIM / phone affidavit'],
                    ['name' => 'Current NIN Slip', 'is_required' => true, 'description' => 'Original NIN tracking slip'],
                    ['name' => 'SIM Registration Slip', 'is_required' => false, 'description' => 'Proof of active SIM registration'],
                ],
                'instructions' => 'Ensure the new phone number is active and registered in your name.',
                'sort_order' => 3,
            ],
            [
                'service_id' => $ninService?->id,
                'name' => 'Address / State of Origin Change',
                'slug' => 'nin_address_change',
                'description' => 'Update residential address, LGA, or State of Origin on national identity records.',
                'price' => 3000.00,
                'processing_time' => '24 - 48 Hours',
                'fields' => [
                    ['label' => 'State of Origin', 'placeholder' => 'e.g. Kano, Lagos, Enugu', 'type' => 'text', 'is_required' => true],
                    ['label' => 'Local Government Area (LGA)', 'placeholder' => 'e.g. Ikeja, Nassarawa', 'type' => 'text', 'is_required' => true],
                    ['label' => 'New Residential Address', 'placeholder' => 'House number, street name, town', 'type' => 'text', 'is_required' => true],
                ],
                'required_documents' => [
                    ['name' => 'Certificate of State of Origin', 'is_required' => true, 'description' => 'Signed by Local Government Chairman'],
                    ['name' => 'Utility Bill / Proof of Address', 'is_required' => true, 'description' => 'Recent electricity bill or tenancy agreement'],
                    ['name' => 'Current NIN Slip', 'is_required' => true, 'description' => 'Clear photo of current NIN'],
                ],
                'instructions' => 'Provide clear photo of certificate of origin from your local government.',
                'sort_order' => 4,
            ],
            [
                'service_id' => $ninService?->id,
                'name' => 'Child to Adult NIN Migration',
                'slug' => 'nin_child_to_adult',
                'description' => 'Upgrade minor/child NIN profile to full adult profile with biometrics.',
                'price' => 3500.00,
                'processing_time' => '2 - 4 Days',
                'fields' => [
                    ['label' => 'Child NIN / Tracking Number', 'placeholder' => '11-digit Minor NIN', 'type' => 'text', 'is_required' => true],
                    ['label' => 'Parent / Guardian NIN', 'placeholder' => '11-digit Parent NIN', 'type' => 'text', 'is_required' => true],
                    ['label' => 'Current Age', 'placeholder' => 'Age (must be 16 or above)', 'type' => 'text', 'is_required' => true],
                ],
                'required_documents' => [
                    ['name' => 'Child NIN Slip', 'is_required' => true, 'description' => 'Original minor tracking slip'],
                    ['name' => 'Birth Certificate / Age Declaration', 'is_required' => true, 'description' => 'NPC birth certificate'],
                    ['name' => 'Parent NIN Slip', 'is_required' => false, 'description' => 'Copy of parent identification slip'],
                ],
                'instructions' => 'Provide child NIN and proof of age.',
                'sort_order' => 5,
            ],
            [
                'service_id' => $ninService?->id,
                'name' => 'Suspended NIN Revalidation',
                'slug' => 'nin_suspended_revalidation',
                'description' => 'Clear suspension, resolve duplicate records, and reactivate dormant NIN.',
                'price' => 5000.00,
                'processing_time' => '3 - 5 Days',
                'fields' => [
                    ['label' => 'Tracking ID on Initial Slip', 'placeholder' => 'e.g. T-XXXX-XXXX', 'type' => 'text', 'is_required' => false],
                    ['label' => 'Description of Suspension / Error', 'placeholder' => 'e.g. Double enrollment, biometric mismatch', 'type' => 'text', 'is_required' => true],
                ],
                'required_documents' => [
                    ['name' => 'Current NIN Slip', 'is_required' => true, 'description' => 'Tracking slip with suspension notice'],
                    ['name' => 'Valid Government ID / BVN Slip', 'is_required' => true, 'description' => 'Voters card, passport, or BVN slip'],
                ],
                'instructions' => 'Upload original NIN tracking slip and valid ID.',
                'sort_order' => 6,
            ],

            // BVN Presets
            [
                'service_id' => $bvnService?->id,
                'name' => 'BVN Name Correction',
                'slug' => 'bvn_name_correction',
                'description' => 'Update or correct name spelling on Bank Verification Number.',
                'price' => 4000.00,
                'processing_time' => '24 - 48 Hours',
                'fields' => [
                    ['label' => 'Old Name on BVN', 'placeholder' => 'Current name showing on bank account', 'type' => 'text', 'is_required' => true],
                    ['label' => 'New Name on BVN', 'placeholder' => 'Correct name matching your NIN records', 'type' => 'text', 'is_required' => true],
                    ['label' => 'Bank Name & Account Number', 'placeholder' => 'e.g. GTBank - 0123456789', 'type' => 'text', 'is_required' => true],
                ],
                'required_documents' => [
                    ['name' => 'NIMC NIN Slip', 'is_required' => true, 'description' => 'NIN slip with exact name requested'],
                    ['name' => 'Court Affidavit', 'is_required' => true, 'description' => 'Affidavit for name correction'],
                    ['name' => 'Bank Account Statement', 'is_required' => false, 'description' => 'First page showing account details'],
                ],
                'instructions' => 'Name on NIN must match the requested BVN change.',
                'sort_order' => 7,
            ],
            [
                'service_id' => $bvnService?->id,
                'name' => 'BVN Date of Birth Correction',
                'slug' => 'bvn_dob_correction',
                'description' => 'Align BVN date of birth with verified NIMC identity records.',
                'price' => 4500.00,
                'processing_time' => '2 - 4 Days',
                'fields' => [
                    ['label' => 'Wrong Date of Birth on BVN', 'placeholder' => 'YYYY-MM-DD', 'type' => 'date', 'is_required' => true],
                    ['label' => 'Correct Date of Birth', 'placeholder' => 'YYYY-MM-DD', 'type' => 'date', 'is_required' => true],
                ],
                'required_documents' => [
                    ['name' => 'NIMC NIN Slip', 'is_required' => true, 'description' => 'Verified NIN matching correct DOB'],
                    ['name' => 'NPC Birth Certificate', 'is_required' => true, 'description' => 'NPC certificate or age declaration'],
                    ['name' => 'Court Affidavit', 'is_required' => true, 'description' => 'Age declaration affidavit'],
                ],
                'instructions' => 'Date of birth on NIN must match the correction requested.',
                'sort_order' => 8,
            ],
            [
                'service_id' => $bvnService?->id,
                'name' => 'BVN Phone Number Update',
                'slug' => 'bvn_phone_update',
                'description' => 'Update registered telephone number for BVN alerts and mobile banking.',
                'price' => 3000.00,
                'processing_time' => '24 - 48 Hours',
                'fields' => [
                    ['label' => 'Old Phone Number on BVN', 'placeholder' => 'Previous phone (if known)', 'type' => 'text', 'is_required' => false],
                    ['label' => 'New Phone Number to Link', 'placeholder' => 'New 11-digit phone number', 'type' => 'text', 'is_required' => true],
                    ['label' => 'Associated Bank Account', 'placeholder' => 'e.g. Access Bank - 0987654321', 'type' => 'text', 'is_required' => true],
                ],
                'required_documents' => [
                    ['name' => 'NIMC NIN Slip', 'is_required' => true, 'description' => 'NIN slip with current photo'],
                    ['name' => 'SIM Registration Slip / Telco ID', 'is_required' => false, 'description' => 'Proof of active SIM registration'],
                ],
                'instructions' => 'Provide active phone number registered in your name.',
                'sort_order' => 9,
            ],
        ];

        foreach ($presets as $preset) {
            if (empty($preset['service_id'])) {
                continue;
            }

            self::updateOrCreate(
                [
                    'store_id' => $store->id,
                    'slug' => $preset['slug'],
                ],
                array_merge($preset, ['store_id' => $store->id, 'is_active' => true])
            );
        }
    }
}
