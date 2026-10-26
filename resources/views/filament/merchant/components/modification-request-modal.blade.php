@php
    $status = $record->status;
    $statusColor = match($status) {
        'completed' => ['bg' => '#ecfdf5', 'text' => '#065f46', 'border' => '#a7f3d0', 'label' => 'Completed'],
        'processing' => ['bg' => '#eff6ff', 'text' => '#1e40af', 'border' => '#bfdbfe', 'label' => 'Processing'],
        'rejected' => ['bg' => '#fef2f2', 'text' => '#991b1b', 'border' => '#fecaca', 'label' => 'Rejected'],
        default => ['bg' => '#fffbeb', 'text' => '#92400e', 'border' => '#fde68a', 'label' => 'Pending Review'],
    };

    $displayFields = $record->submitted_fields ?? $record->new_data ?? [];
    if (empty($displayFields) && !empty($record->old_data)) {
        $displayFields = array_merge($record->old_data, $displayFields);
    }
@endphp

<div style="font-family: inherit; display: flex; flex-direction: column; gap: 18px; color: #1e293b;">
    {{-- Top Summary Bar --}}
    <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; padding: 14px 18px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
        <div>
            <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; font-weight: 600;">Reference</div>
            <div style="font-size: 15px; font-weight: 700; color: #0f172a; font-family: monospace;">{{ $record->reference }}</div>
        </div>
        <div>
            <span style="display: inline-block; padding: 4px 12px; font-size: 12px; font-weight: 700; border-radius: 9999px; background: {{ $statusColor['bg'] }}; color: {{ $statusColor['text'] }}; border: 1px solid {{ $statusColor['border'] }}; text-transform: uppercase; letter-spacing: 0.04em;">
                {{ $statusColor['label'] }}
            </span>
        </div>
    </div>

    {{-- 3-Column Key Information Cards --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px;">
        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px;">
            <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: #64748b; font-weight: 600;">Customer</div>
            <div style="font-size: 14px; font-weight: 700; color: #0f172a; margin-top: 2px;">{{ $record->user?->name ?? 'Customer' }}</div>
            <div style="font-size: 12px; color: #64748b; margin-top: 2px;">{{ $record->user?->phone ?? $record->user?->email }}</div>
        </div>

        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px;">
            <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: #64748b; font-weight: 600;">Target {{ str_contains($record->service_name, 'BVN') ? 'BVN' : 'NIN' }}</div>
            <div style="font-size: 14px; font-weight: 700; color: #0f172a; margin-top: 2px; font-family: monospace; letter-spacing: 0.05em;">{{ $record->identifier }}</div>
            <div style="font-size: 12px; color: #0284c7; font-weight: 600; margin-top: 2px;">{{ $record->service_name }}</div>
        </div>

        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 12px 14px;">
            <div style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: #64748b; font-weight: 600;">Amount Paid</div>
            <div style="font-size: 15px; font-weight: 700; color: #059669; margin-top: 2px;">₦{{ number_format((float) $record->amount_paid, 2) }}</div>
            <div style="font-size: 11px; color: #64748b; margin-top: 2px;">{{ $record->created_at->format('M d, Y · h:i A') }}</div>
        </div>
    </div>

    {{-- Customer Submitted Details --}}
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid #f1f5f9;">
            <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #0284c7;"></span>
            <h4 style="margin: 0; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #334155;">Customer Submitted Information</h4>
        </div>

        @if (!empty($displayFields) && is_array($displayFields))
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 10px;">
                @foreach ($displayFields as $key => $value)
                    @if (!empty($value))
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 12px;">
                            <div style="font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.04em;">
                                {{ is_string($key) ? ucwords(str_replace(['_', '-'], ' ', $key)) : "Field #{$loop->iteration}" }}
                            </div>
                            <div style="font-size: 13px; font-weight: 600; color: #0f172a; margin-top: 4px; word-break: break-word;">
                                {{ $value }}
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            <div style="font-size: 12px; color: #94a3b8; font-style: italic; padding: 8px 0;">No specific data fields attached to this request.</div>
        @endif
    </div>

    {{-- Supporting Documents Uploaded --}}
    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid #f1f5f9;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: #8b5cf6;"></span>
                <h4 style="margin: 0; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #334155;">Supporting Documents ({{ !empty($record->uploaded_documents) ? count($record->uploaded_documents) : 0 }})</h4>
            </div>
            <span style="font-size: 11px; color: #64748b;">Click View to open or save full file</span>
        </div>

        @if (!empty($record->uploaded_documents) && is_array($record->uploaded_documents))
            <div style="display: flex; flex-direction: column; gap: 10px;">
                @foreach ($record->uploaded_documents as $docName => $filePath)
                    @php
                        $fileUrl = \Illuminate\Support\Facades\Storage::disk('central_public')->url($filePath);
                        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'webp']);
                    @endphp
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 10px 14px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;">
                        <div style="display: flex; align-items: center; gap: 12px; min-width: 0;">
                            @if ($isImage)
                                <img 
                                    src="{{ $fileUrl }}" 
                                    alt="Thumbnail" 
                                    style="width: 44px; height: 44px; min-width: 44px; max-width: 44px; object-fit: cover; border-radius: 6px; border: 1px solid #cbd5e1;"
                                />
                            @else
                                <div style="width: 44px; height: 44px; min-width: 44px; max-width: 44px; border-radius: 6px; background: #e0e7ff; color: #4338ca; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 11px;">
                                    PDF
                                </div>
                            @endif

                            <div style="min-width: 0;">
                                <div style="font-size: 13px; font-weight: 700; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $docName }}</div>
                                <div style="font-size: 11px; color: #64748b; font-family: monospace; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin-top: 2px;">{{ basename($filePath) }}</div>
                            </div>
                        </div>

                        <a 
                            href="{{ $fileUrl }}" 
                            target="_blank" 
                            rel="noopener noreferrer"
                            style="display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; font-size: 12px; font-weight: 600; color: #0284c7; background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 6px; text-decoration: none; white-space: nowrap;"
                        >
                            <span>Open Document</span>
                            <span style="font-size: 13px; line-height: 1;">&rarr;</span>
                        </a>
                    </div>
                @endforeach
            </div>
        @else
            <div style="font-size: 12px; color: #94a3b8; font-style: italic; padding: 8px 0;">No documents uploaded with this request.</div>
        @endif
    </div>

    {{-- Result Slip (if completed) --}}
    @if ($record->result_file_path)
        <div style="background: #f5f3ff; border: 1px solid #ddd6fe; border-radius: 12px; padding: 14px 18px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;">
            <div>
                <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #6d28d9; letter-spacing: 0.05em;">Processed Result Slip / Slip Issued</div>
                <div style="font-size: 12px; color: #475569; font-family: monospace; margin-top: 2px;">{{ basename($record->result_file_path) }}</div>
            </div>
            <a 
                href="{{ \Illuminate\Support\Facades\Storage::disk('central_public')->url($record->result_file_path) }}" 
                target="_blank" 
                download
                style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #7c3aed; color: #ffffff; font-size: 12px; font-weight: 700; border-radius: 8px; text-decoration: none;"
            >
                Download Slip
            </a>
        </div>
    @endif

    {{-- Admin Notes or Rejection Reason --}}
    @if (!empty($record->rejection_reason))
        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 12px 16px;">
            <div style="font-size: 11px; font-weight: 700; color: #991b1b; text-transform: uppercase;">Rejection Reason</div>
            <div style="font-size: 13px; color: #7f1d1d; margin-top: 4px;">{{ $record->rejection_reason }}</div>
        </div>
    @elseif (!empty($record->admin_notes))
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 12px 16px;">
            <div style="font-size: 11px; font-weight: 700; color: #166534; text-transform: uppercase;">Processing Notes</div>
            <div style="font-size: 13px; color: #14532d; margin-top: 4px;">{{ $record->admin_notes }}</div>
        </div>
    @endif
</div>
