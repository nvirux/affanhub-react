<div class="space-y-4">
    <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex items-center justify-center p-2">
        @if ($record->proof_image)
            <img 
                src="{{ asset('storage/' . $record->proof_image) }}" 
                alt="Transfer Proof Screenshot" 
                class="max-h-96 w-auto object-contain rounded-lg shadow-sm"
            />
        @else
            <p class="text-sm text-gray-400 py-8">No proof image uploaded for this order.</p>
        @endif
    </div>

    @if ($record->proof_image)
        <div class="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-gray-800 text-xs text-gray-500">
            <span>Customer SIM: <strong class="text-gray-900 dark:text-gray-100 font-mono">{{ $record->sender_phone }}</strong></span>
            <a 
                href="{{ asset('storage/' . $record->proof_image) }}" 
                target="_blank" 
                rel="noopener noreferrer" 
                class="inline-flex items-center gap-1 text-primary-600 hover:text-primary-500 font-medium underline"
            >
                Open in Full Size &rarr;
            </a>
        </div>
    @endif
</div>
