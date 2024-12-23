<?php

namespace App\Filament\Resources\SousTraitantResource\Pages;

use App\Filament\Resources\SousTraitantResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSousTraitant extends EditRecord
{
    protected static string $resource = SousTraitantResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
