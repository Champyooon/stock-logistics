<?php

namespace App\Filament\Resources\AchatComptabiliteResource\Pages;

use App\Filament\Resources\AchatComptabiliteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAchatComptabilite extends EditRecord
{
    protected static string $resource = AchatComptabiliteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
