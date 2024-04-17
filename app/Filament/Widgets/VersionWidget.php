<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class VersionWidget extends Widget
{
    protected static ?int $sort = -2;

    protected static bool $isLazy = false;

    /**
     * @var view-string
     */
    protected static string $view = 'widgets.version-widget';
}