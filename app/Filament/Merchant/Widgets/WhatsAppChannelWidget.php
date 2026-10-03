<?php

namespace App\Filament\Merchant\Widgets;

use Filament\Widgets\Widget;

class WhatsAppChannelWidget extends Widget
{
    protected static ?int $sort = 0;

    protected string $view = 'filament.merchant.widgets.whatsapp-channel';

    protected int|string|array $columnSpan = 'full';
}
