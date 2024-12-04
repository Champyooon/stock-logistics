<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestAdminEmployees extends BaseWidget
{
    protected static ?string $heading = 'Liste des derniers des employés';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';
    protected function getTableQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return Employee::query()
            ->orderBy('created_at', 'desc');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('department.name')
                ->label('Nom du département'),
            Tables\Columns\TextColumn::make('first_name')
                ->label('Prénom'),
            Tables\Columns\TextColumn::make('last_name')
                ->label('Nom'),
        ];
    }
}
