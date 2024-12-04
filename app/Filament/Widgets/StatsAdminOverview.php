<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use App\Models\Inventory;
use App\Models\Material;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsAdminOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Employés', Employee::count())
            ->description('Employés à Bloomtide')
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([7, 2, 10, 3, 15, 4, 17])
            ->color('success'),
        Stat::make('Clients', Inventory::count())
            ->description("2% en hausse")
            ->descriptionIcon('heroicon-m-arrow-trending-up')
            ->chart([7, 2, 10, 8, 15, 19, 17])
            ->color('success'),
        Stat::make('Materiel', Material::count())
            ->description('3% défectueux')
            ->descriptionIcon('heroicon-m-arrow-trending-down')
            ->chart([15, 2, 10, 3, 15, 4, 1])
            ->color('danger'),
        ];
    }
}
