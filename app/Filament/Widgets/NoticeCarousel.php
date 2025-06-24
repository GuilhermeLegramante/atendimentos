<?php

namespace App\Filament\Widgets;

use App\Models\Notice;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class NoticeCarousel extends BaseWidget
{
    protected static string $view = 'widgets.notice-carousel';

    protected int|string|array $columnSpan = 'full';

    public function getNotices()
    {
        return Notice::latest()->take(10)->get();
    }
}
