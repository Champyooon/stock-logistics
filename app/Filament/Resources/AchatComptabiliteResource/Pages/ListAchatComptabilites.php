<?php

namespace App\Filament\Resources\AchatComptabiliteResource\Pages;

use App\Filament\Resources\AchatComptabiliteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAchatComptabilites extends ListRecords
{
    protected static string $resource = AchatComptabiliteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
