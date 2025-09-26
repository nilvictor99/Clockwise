<?php

namespace App\Filament\Widgets;

use App\Models\PasswordVault;
use App\Models\Timesheet;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Usuarios registrados', User::count()),
            Stat::make('Contraseñas guardadas', PasswordVault::count()),
            Stat::make('Asistencias registradas', Timesheet::count()),
        ];
    }
}
