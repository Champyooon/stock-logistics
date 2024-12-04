<?php

namespace App\Filament\Widgets;


use App\Models\Vehicule;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class VehiculeAdminChart extends ChartWidget
{
    protected static ?string $heading = 'Graphique des véhicules';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = Trend::model(Vehicule::class)
        ->between(
            start: now()->startOfMonth(),
            end: now()->endOfMonth(),
        )
        ->perDay()
        ->count();

    return [
        'datasets' => [
            [
                'label' => 'Véhicules',
                'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
            ],
        ],
        'labels' => $data->map(fn (TrendValue $value) => $value->date),
    ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
