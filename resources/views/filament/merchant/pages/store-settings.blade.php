<x-filament-panels::page>
    @php
        $tenant = \Filament\Facades\Filament::getTenant();
    @endphp

    <div style="font-family: inherit; display: flex; flex-direction: column; gap: 2rem;">
        
        <form wire:submit.prevent="saveSettings" style="display: flex; flex-direction: column; gap: 2rem;">
            
            {{-- Section 1: Store Information --}}
            <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 16px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); display: flex; flex-direction: column; gap: 1.5rem;">
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0;">Store Profile</h3>
                    <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">Update your public storefront name and description.</p>
                </div>

                <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 0;">

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    {{-- Store Logo Upload --}}
                    <div style="display: flex; flex-direction: column; gap: 0.5rem; grid-column: span 2;">
                        <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">Store Logo</label>
                        <div style="display: flex; align-items: center; gap: 1.5rem; background: #f9fafb; padding: 1rem; border-radius: 12px; border: 1px dashed #d1d5db;">
                            @if ($logoPath)
                                <div style="position: relative; width: 64px; height: 64px; border-radius: 8px; overflow: hidden; background: white; border: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: center;">
                                    <img src="{{ asset('storage/' . $logoPath) }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                </div>
                                <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                    <span style="font-size: 0.875rem; font-weight: 600; color: #374151;">Logo uploaded</span>
                                    <button type="button" wire:click="removeLogo" style="font-size: 0.75rem; font-weight: 700; color: #dc2626; text-align: left; background: none; border: none; cursor: pointer; padding: 0;">Remove Logo</button>
                                </div>
                            @elseif ($logo)
                                <div style="position: relative; width: 64px; height: 64px; border-radius: 8px; overflow: hidden; background: white; border: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: center;">
                                    <img src="{{ $logo->temporaryUrl() }}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                </div>
                                <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                    <span style="font-size: 0.875rem; font-weight: 600; color: #374151;">Preview logo</span>
                                    <span style="font-size: 0.75rem; color: #6b7280;">Ready to save</span>
                                </div>
                            @else
                                <div style="width: 64px; height: 64px; border-radius: 8px; background: #e5e7eb; display: flex; align-items: center; justify-content: center; color: #9ca3af; font-size: 1.25rem; font-weight: 800;">
                                    {{ substr($name, 0, 1) ?: 'S' }}
                                </div>
                                <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                    <span style="font-size: 0.875rem; font-weight: 600; color: #374151;">No logo uploaded</span>
                                    <span style="font-size: 0.75rem; color: #6b7280;">Upload a square PNG or JPG, max 1MB.</span>
                                </div>
                            @endif
                            
                            <div style="margin-left: auto;">
                                <input type="file" id="logoInput" wire:model="logo" style="display: none;" accept="image/*">
                                <label for="logoInput" style="background-color: white; border: 1px solid #d1d5db; border-radius: 8px; padding: 0.5rem 1rem; font-size: 0.75rem; font-weight: 700; color: #374151; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                                    Upload File
                                </label>
                            </div>
                        </div>
                        @error('logo') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                    </div>

                    {{-- Store Name --}}
                    <div style="display: flex; flex-direction: column; gap: 0.5rem; grid-column: span 2;">
                        <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">Store Name <span style="color: #dc2626;">*</span></label>
                        <input 
                            type="text" 
                            wire:model="name" 
                            placeholder="e.g. My Premium Store" 
                            style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 0.75rem 1rem; font-size: 0.875rem; color: #111827; outline: none; transition: border-color 0.2s;"
                        />
                        @error('name') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                    </div>

                    {{-- Store Subdomain (Read only) --}}
                    <div style="display: flex; flex-direction: column; gap: 0.5rem; grid-column: span 2;">
                        <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">Store Subdomain (Unique ID)</label>
                        <input 
                            type="text" 
                            value="{{ $tenant->id }}.localhost:8000" 
                            disabled 
                            style="width: 100%; border: 1px solid #e5e7eb; background-color: #f9fafb; border-radius: 12px; padding: 0.75rem 1rem; font-size: 0.875rem; color: #6b7280; cursor: not-allowed;"
                        />
                        <span style="font-size: 0.75rem; color: #9ca3af;">Subdomain cannot be changed directly. Contact support if you need to modify this.</span>
                    </div>

                    {{-- Description --}}
                    <div style="display: flex; flex-direction: column; gap: 0.5rem; grid-column: span 2;">
                        <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">Store Description</label>
                        <textarea 
                            wire:model="description" 
                            rows="4" 
                            placeholder="Tell your customers about your shop, brand values, or catalog..." 
                            style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 0.75rem 1rem; font-size: 0.875rem; color: #111827; outline: none; transition: border-color 0.2s; resize: vertical;"
                        ></textarea>
                        @error('description') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                    </div>

                    {{-- Dashboard Tagline --}}
                    <div style="display: flex; flex-direction: column; gap: 0.5rem; grid-column: span 2;">
                        <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">Dashboard Subtitle / Tagline</label>
                        <input 
                            type="text" 
                            wire:model="dashboardSubtitle" 
                            placeholder="e.g. Fast & Reliable Telecom Services" 
                            maxlength="40"
                            style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 0.75rem 1rem; font-size: 0.875rem; color: #111827; outline: none; transition: border-color 0.2s;"
                        />
                        <span style="font-size: 0.75rem; color: #9ca3af;">This will be displayed as a friendly tagline below the welcome greeting on your customers' mobile dashboard (max 40 characters).</span>
                        @error('dashboardSubtitle') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- Section 2: Contact Details --}}
            <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 16px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); display: flex; flex-direction: column; gap: 1.5rem;">
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0;">Contact Details</h3>
                    <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">Customer support contact details shown on your storefront.</p>
                </div>

                <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 0;">

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                    {{-- Contact Email --}}
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">Support Email Address</label>
                        <input 
                            type="email" 
                            wire:model="contactEmail" 
                            placeholder="e.g. support@mybrand.com" 
                            style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 0.75rem 1rem; font-size: 0.875rem; color: #111827; outline: none; transition: border-color 0.2s;"
                        />
                        @error('contactEmail') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                    </div>

                    {{-- Contact Phone --}}
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">Support Phone Number</label>
                        <input 
                            type="text" 
                            wire:model="contactPhone" 
                            placeholder="e.g. +1 (555) 019-2834" 
                            style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 0.75rem 1rem; font-size: 0.875rem; color: #111827; outline: none; transition: border-color 0.2s;"
                        />
                        @error('contactPhone') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- Section 3: Social Connections --}}
            <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 16px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); display: flex; flex-direction: column; gap: 1.5rem;">
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0;">Social Connections</h3>
                    <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">Link your social media profiles to display social icons on your storefront.</p>
                </div>

                <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 0;">

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                    {{-- Instagram --}}
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">Instagram Username</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <span style="position: absolute; left: 1rem; color: #9ca3af; font-size: 0.875rem; pointer-events: none;">@</span>
                            <input 
                                type="text" 
                                wire:model="socialInstagram" 
                                placeholder="mybrand" 
                                style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 0.75rem 1rem 0.75rem 2rem; font-size: 0.875rem; color: #111827; outline: none;"
                            />
                        </div>
                        @error('socialInstagram') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                    </div>

                    {{-- Facebook --}}
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">Facebook Page URL</label>
                        <input 
                            type="text" 
                            wire:model="socialFacebook" 
                            placeholder="https://facebook.com/mybrand" 
                            style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 0.75rem 1rem; font-size: 0.875rem; color: #111827; outline: none;"
                        />
                        @error('socialFacebook') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                    </div>

                    {{-- WhatsApp --}}
                    <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                        <label style="font-size: 0.875rem; font-weight: 700; color: #374151;">WhatsApp Number</label>
                        <input 
                            type="text" 
                            wire:model="socialWhatsapp" 
                            placeholder="e.g. +15550192834" 
                            style="width: 100%; border: 1px solid #d1d5db; border-radius: 12px; padding: 0.75rem 1rem; font-size: 0.875rem; color: #111827; outline: none;"
                        />
                        @error('socialWhatsapp') <span style="font-size: 0.75rem; color: #dc2626; margin-top: 0.25rem;">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            {{-- Section 4: Live Chat & Support --}}
            <div style="background-color: white; border: 1px solid rgba(0, 0, 0, 0.08); border-radius: 16px; padding: 2rem; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03); display: flex; flex-direction: column; gap: 1.5rem;">
                <div>
                    <h3 style="font-size: 1.125rem; font-weight: 800; color: #111827; margin: 0;">Live Chat & Customer Support</h3>
                    <p style="font-size: 0.875rem; color: #6b7280; margin-top: 0.25rem; margin-bottom: 0;">Embed real-time support widgets to assist customers directly on your storefront.</p>
                </div>

                <hr style="border: 0; border-top: 1px solid #f3f4f6; margin: 0;">

                <div style="display: flex; flex-direction: column; gap: 2rem;">
                    {{-- WhatsApp floating support --}}
                    <div style="display: flex; flex-direction: column; gap: 1rem; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 1.5rem; border-radius: 12px;">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <h4 style="font-size: 0.95rem; font-weight: 800; color: #14532d; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                                    <svg style="width: 18px; height: 18px; fill: #15803d;" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.713-1.458L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.963C16.588 2.02 14.12 1.002 11.5 1.002c-5.442 0-9.87 4.372-9.874 9.802-.001 1.769.471 3.498 1.362 5.031L2.002 21.5l5.727-1.492zm11.233-5.918c-.3-.15-1.771-.875-2.046-.975-.276-.1-.477-.15-.677.15-.2.3-.777.975-.951 1.175-.175.2-.35.225-.65.075-3.04-1.522-4.14-2.522-4.9-3.825-.2-.35-.022-.538.127-.687.135-.135.3-.35.45-.525.15-.175.2-.3.3-.5.1-.2.05-.375-.025-.525-.075-.15-.677-1.625-.926-2.225-.244-.589-.493-.51-.677-.52l-.578-.01c-.2 0-.525.075-.8.375-.275.3-1.05 1.025-1.05 2.5s1.07 2.9 1.22 3.1c.15.2 2.105 3.213 5.098 4.5 1.205.52 2.146.83 2.875 1.06.73.23 1.396.197 1.92.12.584-.087 1.771-.725 2.021-1.425.25-.7.25-1.3 1.75-1.425-.075-.125-.375-.275-.675-.425z"/></svg>
                                    WhatsApp Support Bubble
                                </h4>
                                <p style="font-size: 0.825rem; color: #166534; margin: 0.25rem 0 0 0;">Add a floating WhatsApp chat button to the bottom corner of your storefront.</p>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <input type="checkbox" wire:model.live="whatsappChatEnabled" style="width: 1.25rem; height: 1.25rem; accent-color: #15803d; cursor: pointer;">
                            </div>
                        </div>
                        
                        @if ($whatsappChatEnabled)
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem; margin-top: 0.5rem; border-top: 1px dashed #bbf7d0; padding-top: 1rem;">
                                <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                    <label style="font-size: 0.75rem; font-weight: 700; color: #14532d;">WhatsApp Phone Number</label>
                                    <input 
                                        type="text" 
                                        wire:model="whatsappChatPhone" 
                                        placeholder="e.g. 2348123456789" 
                                        style="border: 1px solid #86efac; border-radius: 8px; padding: 0.5rem; font-size: 0.825rem; color: #14532d; outline: none; background: white;"
                                    />
                                    <span style="font-size: 0.7rem; color: #166534;">Enter with country code, no + signs, dashes or leading 0 (e.g. 234...).</span>
                                </div>
                                <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                    <label style="font-size: 0.75rem; font-weight: 700; color: #14532d;">Pre-filled Chat Message</label>
                                    <input 
                                        type="text" 
                                        wire:model="whatsappChatMessage" 
                                        placeholder="e.g. Hello, I need assistance." 
                                        style="border: 1px solid #86efac; border-radius: 8px; padding: 0.5rem; font-size: 0.825rem; color: #14532d; outline: none; background: white;"
                                    />
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Tawk.to live chat widget --}}
                    <div style="display: flex; flex-direction: column; gap: 1rem; background: #eff6ff; border: 1px solid #bfdbfe; padding: 1.5rem; border-radius: 12px;">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <h4 style="font-size: 0.95rem; font-weight: 800; color: #1e3a8a; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                                    <svg style="width: 18px; height: 18px; fill: #2563eb;" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm8 5H6v-2h8v2zm4-6H6V6h12v2z"/></svg>
                                    Tawk.to Live Chat Widget
                                </h4>
                                <p style="font-size: 0.825rem; color: #1e40af; margin: 0.25rem 0 0 0;">Inject a Tawk.to chat widget for professional, real-time live support on your storefront.</p>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <input type="checkbox" wire:model.live="tawkChatEnabled" style="width: 1.25rem; height: 1.25rem; accent-color: #2563eb; cursor: pointer;">
                            </div>
                        </div>
                        
                        @if ($tawkChatEnabled)
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem; margin-top: 0.5rem; border-top: 1px dashed #bfdbfe; padding-top: 1rem;">
                                <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                    <label style="font-size: 0.75rem; font-weight: 700; color: #1e3a8a;">Tawk.to Property ID</label>
                                    <input 
                                        type="text" 
                                        wire:model="tawkPropertyId" 
                                        placeholder="e.g. 64b8d78994af5e1234567890" 
                                        style="border: 1px solid #93c5fd; border-radius: 8px; padding: 0.5rem; font-size: 0.825rem; color: #1e3a8a; outline: none; background: white;"
                                    />
                                    <span style="font-size: 0.7rem; color: #1e40af;">You can find this inside your Tawk.to dashboard under Property settings.</span>
                                </div>
                                <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                    <label style="font-size: 0.75rem; font-weight: 700; color: #1e3a8a;">Tawk.to Widget ID</label>
                                    <input 
                                        type="text" 
                                        wire:model="tawkWidgetId" 
                                        placeholder="e.g. default" 
                                        style="border: 1px solid #93c5fd; border-radius: 8px; padding: 0.5rem; font-size: 0.825rem; color: #1e3a8a; outline: none; background: white;"
                                    />
                                    <span style="font-size: 0.7rem; color: #1e40af;">Typically 'default' or a short alphanumeric code next to the property.</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Submit button --}}
            <div style="display: flex; justify-content: flex-end; gap: 1rem; margin-top: 1rem;">
                <button type="submit" wire:loading.attr="disabled" style="background-color: #f59e0b; color: white; font-weight: 700; font-size: 0.875rem; padding: 0.75rem 2rem; border: none; border-radius: 12px; cursor: pointer; transition: background-color 0.2s; box-shadow: 0 4px 6px rgba(245, 158, 11, 0.15);">
                    <span wire:loading.remove wire:target="saveSettings">Save Settings</span>
                    <span wire:loading wire:target="saveSettings">Saving...</span>
                </button>
            </div>

        </form>
    </div>
</x-filament-panels::page>
