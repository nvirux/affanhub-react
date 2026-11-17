<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class StoreCacService extends Model
{
    protected $table = 'store_cac_services';

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

    protected static function booted(): void
    {
        static::creating(function (StoreCacService $service) {
            if (empty($service->store_id) && class_exists(Filament::class) && Filament::getTenant()) {
                $service->store_id = Filament::getTenant()->id;
            }

            if (empty($service->slug) && filled($service->name)) {
                $baseSlug = Str::slug($service->name, '_');
                if (empty($baseSlug)) {
                    $baseSlug = 'cac_service';
                }

                if (! str_starts_with($baseSlug, 'cac_')) {
                    $baseSlug = 'cac_'.$baseSlug;
                }

                $slugCandidate = $baseSlug;
                $slug = $slugCandidate;
                $counter = 1;

                while (static::where('store_id', $service->store_id)->where('slug', $slug)->exists()) {
                    $slug = $slugCandidate.'_'.$counter;
                    $counter++;
                }

                $service->slug = $slug;
            }
        });
    }

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
        return $this->hasMany(CacRequest::class, 'store_cac_service_id');
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
     * Auto-seed default CAC packages for a store.
     */
    public static function seedDefaultsForStore(Store $store): void
    {
        $cacService = Service::where('key', 'cac_registration')->first();

        $presets = [
            [
                'service_id' => $cacService?->id,
                'name' => 'Business Name Registration',
                'slug' => 'cac_business_name',
                'description' => 'Register your enterprise or enterprise venture with the CAC to receive official Certificate & Tax Identification Number (TIN).',
                'price' => 25000.00,
                'processing_time' => '3 - 5 Days',
                'fields' => [
                    ['label' => '1st Proposed Business Name', 'placeholder' => 'e.g. Acme Global Ventures', 'type' => 'text', 'is_required' => true],
                    ['label' => '2nd Proposed Name (Alternative)', 'placeholder' => 'e.g. Acme Enterprises', 'type' => 'text', 'is_required' => true],
                    ['label' => 'Nature of Business', 'placeholder' => 'e.g. General Merchandise, IT Services, Agriculture', 'type' => 'text', 'is_required' => true],
                    ['label' => 'Business Office Address', 'placeholder' => 'Street address, City, State', 'type' => 'text', 'is_required' => true],
                    ['label' => 'Proprietor Full Name', 'placeholder' => 'Surname First Name Other Names', 'type' => 'text', 'is_required' => true],
                    ['label' => 'Proprietor National ID Number (NIN)', 'placeholder' => '11-digit NIN', 'type' => 'text', 'is_required' => true],
                    ['label' => 'Proprietor Active Phone Number', 'placeholder' => '080XXXXXXXX', 'type' => 'text', 'is_required' => true],
                    ['label' => 'Proprietor Email Address', 'placeholder' => 'user@example.com', 'type' => 'text', 'is_required' => true],
                    ['label' => 'Proprietor Residential Address', 'placeholder' => 'Full residential home address', 'type' => 'text', 'is_required' => true],
                ],
                'required_documents' => [
                    ['name' => 'Means of Identification', 'is_required' => true, 'description' => 'NIN Slip, Voter\'s Card, Driver\'s License, or Int\'l Passport'],
                    ['name' => 'Passport Photograph', 'is_required' => true, 'description' => 'Clear passport photo on white background'],
                    ['name' => 'Specimen Signature', 'is_required' => true, 'description' => 'Clear signature signed on plain white paper'],
                ],
                'instructions' => 'Provide two distinct, unique proposed business names. Ensure all proprietor information matches your valid identification card.',
                'sort_order' => 1,
            ],
            [
                'service_id' => $cacService?->id,
                'name' => 'Company Registration (Ltd)',
                'slug' => 'cac_company_ltd',
                'description' => 'Incorporate a Private Limited Liability Company (Ltd) with authorized share capital, MEMART, and status report.',
                'price' => 65000.00,
                'processing_time' => '5 - 7 Days',
                'fields' => [
                    ['label' => '1st Proposed Company Name', 'placeholder' => 'e.g. Acme Technologies Ltd', 'type' => 'text', 'is_required' => true],
                    ['label' => '2nd Proposed Name (Alternative)', 'placeholder' => 'e.g. Acme Digital Solutions Ltd', 'type' => 'text', 'is_required' => true],
                    ['label' => 'Main Objects / Nature of Business', 'placeholder' => 'Comprehensive description of business activities', 'type' => 'textarea', 'is_required' => true],
                    ['label' => 'Registered Office Address', 'placeholder' => 'Head office address in Nigeria', 'type' => 'text', 'is_required' => true],
                    ['label' => 'Authorized Share Capital', 'placeholder' => 'e.g. 1,000,000 Shares of ₦1.00 each', 'type' => 'text', 'is_required' => true],
                    ['label' => 'Director 1 Full Name & NIN', 'placeholder' => 'Full legal name and 11-digit NIN', 'type' => 'text', 'is_required' => true],
                    ['label' => 'Director 2 Full Name & NIN (Optional)', 'placeholder' => 'Full legal name and NIN of 2nd director', 'type' => 'text', 'is_required' => false],
                    ['label' => 'Company Email Address', 'placeholder' => 'company@example.com', 'type' => 'text', 'is_required' => true],
                    ['label' => 'Company Contact Phone', 'placeholder' => 'Official phone number', 'type' => 'text', 'is_required' => true],
                ],
                'required_documents' => [
                    ['name' => 'Valid ID for Directors', 'is_required' => true, 'description' => 'Government issued ID for all listed directors'],
                    ['name' => 'Passport Photos of Directors', 'is_required' => true, 'description' => 'Clear passport photos on white background'],
                    ['name' => 'Signatures of Directors', 'is_required' => true, 'description' => 'Signatures signed on clean white paper'],
                ],
                'instructions' => 'Private companies require minimum 1,000,000 share capital. Provide registered Nigerian office address and valid director IDs.',
                'sort_order' => 2,
            ],
            [
                'service_id' => $cacService?->id,
                'name' => 'CAC Status Report & Document Retrieval',
                'slug' => 'cac_status_report',
                'description' => 'Retrieve official CAC Electronic Status Report, Certificate copy, or registered particulars for any existing entity.',
                'price' => 6000.00,
                'processing_time' => '24 - 48 Hours',
                'fields' => [
                    ['label' => 'Registered Entity Name', 'placeholder' => 'Exact registered name of company or business', 'type' => 'text', 'is_required' => true],
                    ['label' => 'Registration Number (RC or BN)', 'placeholder' => 'e.g. RC1234567 or BN9876543', 'type' => 'text', 'is_required' => true],
                    ['label' => 'Approximate Year of Registration', 'placeholder' => 'e.g. 2021', 'type' => 'text', 'is_required' => false],
                ],
                'required_documents' => [
                    ['name' => 'Previous Slip or Document (Optional)', 'is_required' => false, 'description' => 'Old certificate photo, letterhead, or TIN document if available'],
                ],
                'instructions' => 'Double-check that the RC or BN number is accurate to ensure successful lookup and status report generation.',
                'sort_order' => 3,
            ],
            [
                'service_id' => $cacService?->id,
                'name' => 'Name Availability & Reservation',
                'slug' => 'cac_name_reservation',
                'description' => 'Search and reserve your prospective business or company name on CAC database for 60 days.',
                'price' => 4000.00,
                'processing_time' => '24 Hours',
                'fields' => [
                    ['label' => '1st Preferred Name', 'placeholder' => 'First choice business or company name', 'type' => 'text', 'is_required' => true],
                    ['label' => '2nd Preferred Name (Alternative)', 'placeholder' => 'Alternative choice if 1st name is taken', 'type' => 'text', 'is_required' => true],
                    ['label' => 'Entity Classification', 'placeholder' => 'Business Name / Private Limited Company / Incorporated Trustee', 'type' => 'text', 'is_required' => true],
                ],
                'required_documents' => [],
                'instructions' => 'CAC name reservation reserves the approved name exclusively for you for 60 days before actual registration.',
                'sort_order' => 4,
            ],
        ];

        foreach ($presets as $preset) {
            static::updateOrCreate(
                [
                    'store_id' => $store->id,
                    'slug' => $preset['slug'],
                ],
                array_merge($preset, [
                    'store_id' => $store->id,
                    'is_active' => true,
                ])
            );
        }
    }
}
