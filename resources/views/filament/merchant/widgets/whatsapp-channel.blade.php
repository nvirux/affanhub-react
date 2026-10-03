<x-filament-widgets::widget>
    <style>
        .wa-banner-card {
            background: linear-gradient(135deg, #059669 0%, #047857 55%, #064e3b 100%);
            border-radius: 16px;
            padding: 1.125rem 1.25rem;
            color: #ffffff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 16px -2px rgba(5, 150, 105, 0.25), 0 2px 6px -1px rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.12);
            display: flex;
            flex-direction: column;
            gap: 1rem;
            box-sizing: border-box;
            width: 100%;
        }

        .wa-banner-bg-glow {
            position: absolute;
            top: -40px;
            right: -40px;
            width: 150px;
            height: 150px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.16) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .wa-banner-body {
            display: flex;
            align-items: flex-start;
            gap: 0.875rem;
            width: 100%;
            min-width: 0;
            position: relative;
            z-index: 1;
        }

        .wa-banner-icon-box {
            width: 44px;
            height: 44px;
            min-width: 44px;
            max-width: 44px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .wa-banner-text {
            flex: 1;
            min-width: 0;
        }


        .wa-banner-title {
            font-size: 1.05rem;
            font-weight: 800;
            color: #ffffff;
            margin: 0;
            line-height: 1.3;
            letter-spacing: -0.01em;
        }

        .wa-banner-desc {
            font-size: 0.8125rem;
            color: rgba(255, 255, 255, 0.9);
            margin: 0.375rem 0 0 0;
            line-height: 1.45;
        }

        .wa-banner-action {
            width: 100%;
            position: relative;
            z-index: 1;
        }

        .wa-banner-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background: #ffffff;
            color: #047857;
            font-size: 0.875rem;
            font-weight: 800;
            padding: 0.6875rem 1.25rem;
            border-radius: 11px;
            text-decoration: none;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            box-sizing: border-box;
            transition: all 0.15s ease-in-out;
            text-align: center;
        }

        .wa-banner-btn:hover {
            background: #f0fdf4;
            color: #065f46;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.14);
        }

        .wa-banner-btn:active {
            transform: scale(0.985);
        }

        /* Desktop & Tablet layout (min-width: 640px) */
        @media (min-width: 640px) {
            .wa-banner-card {
                padding: 1.25rem 1.5rem;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                gap: 1.25rem;
            }

            .wa-banner-body {
                align-items: center;
                flex: 1;
            }

            .wa-banner-icon-box {
                width: 48px;
                height: 48px;
                min-width: 48px;
                max-width: 48px;
                border-radius: 13px;
            }

            .wa-banner-title {
                font-size: 1.05rem;
            }

            .wa-banner-desc {
                margin-top: 0.25rem;
            }

            .wa-banner-action {
                width: auto;
                flex-shrink: 0;
            }

            .wa-banner-btn {
                width: auto;
                font-size: 0.8125rem;
                padding: 0.625rem 1.25rem;
            }
        }
    </style>

    <div class="wa-banner-card">
        <div class="wa-banner-bg-glow"></div>

        <!-- Left Section: Icon & Info -->
        <div class="wa-banner-body">
            <div class="wa-banner-icon-box">
                <svg width="26" height="26" style="width: 26px !important; height: 26px !important; display: block; fill: #ffffff; flex-shrink: 0;" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                </svg>
            </div>
            <div class="wa-banner-text">
                <h3 class="wa-banner-title">Official WhatsApp Community</h3>
                <p class="wa-banner-desc">
                    Join our community group for telecom network uptime alerts, provider rate drops, identity services, and general announcements.
                </p>
            </div>
        </div>

        <!-- Right Section: Action Button -->
        <div class="wa-banner-action">
            <a
                href="https://chat.whatsapp.com/BRaczSTbj3fG5CmG3qttf1"
                target="_blank"
                rel="noopener noreferrer"
                class="wa-banner-btn"
            >
                <span>Join Community Group</span>
                <svg width="15" height="15" style="width: 15px !important; height: 15px !important; stroke: currentColor; fill: none; display: block; flex-shrink: 0;" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>
    </div>
</x-filament-widgets::widget>
