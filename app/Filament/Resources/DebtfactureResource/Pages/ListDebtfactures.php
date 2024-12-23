<?php

namespace App\Filament\Resources\DebtfactureResource\Pages;

use App\Filament\Resources\DebtfactureResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDebtfactures extends ListRecords
{
    protected static string $resource = DebtfactureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
