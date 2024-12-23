<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceResource\Pages;
use App\Filament\Resources\MaintenanceResource\RelationManagers;
use App\Models\Maintenance;
use Closure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MaintenanceResource extends Resource
{
    protected static ?string $model = Maintenance::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static array $statusees =[
        'En cours'=>'En cours',
        'Terminée'=>'Terminée'

    ];

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Select::make('vehicule_id')
                ->relationship('vehicule', 'model')
                ->label('Vehicule')
                ->reactive()
                ->afterStateUpdated(function ($state, $set) {
                    // Désactiver les autres champs lorsqu'un véhicule est sélectionné
                    if ($state) {
                        $set('material_id', null); // Réinitialiser l'état de l'autre champ
                        $set('inventory_id', null); // Réinitialiser l'état de l'autre champ
                    }
                }),

            Forms\Components\Select::make('material_id')
                ->relationship('material', 'item_name')
                ->label('Materiel Technicien')
                ->reactive()
                ->afterStateUpdated(function ($state, $set) {
                    // Désactiver les autres champs lorsqu'un matériel est sélectionné
                    if ($state) {
                        $set('vehicule_id', null); // Réinitialiser l'état de l'autre champ
                        $set('inventory_id', null); // Réinitialiser l'état de l'autre champ
                    }
                }),

            Forms\Components\Select::make('inventory_id')
                ->relationship('inventory', 'item_name')
                ->label('Materiel bureautique')
                ->reactive()
                ->afterStateUpdated(function ($state, $set) {
                    // Désactiver les autres champs lorsqu'un matériel bureautique est sélectionné
                    if ($state) {
                        $set('vehicule_id', null); // Réinitialiser l'état de l'autre champ
                        $set('material_id', null); // Réinitialiser l'état de l'autre champ
                    }
                }),

            Forms\Components\TextInput::make('type_maintenance')
                ->required()
                ->maxLength(255),
            Forms\Components\DatePicker::make('date_debut')
                ->required(),
            Forms\Components\DatePicker::make('date_fin'),
            Forms\Components\TextInput::make('responsable')
                ->required()
                ->maxLength(255),
                Forms\Components\Select::make('status')
                ->options(self::$statusees)
                ->required(),
            Forms\Components\Textarea::make('probleme_detecte')
                ->columnSpanFull(),
            Forms\Components\Textarea::make('action_effectuee')
                ->columnSpanFull(),
            Forms\Components\TextInput::make('cout_total')
                ->numeric(),
        ]);
}



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('vehicule.model')
                    ->label('Vehicule')
                    ->sortable(),
                Tables\Columns\TextColumn::make('material.item_name')
                    ->label('Materiel technique')
                    ->sortable(),
                Tables\Columns\TextColumn::make('inventory.item_name')
                    ->label('Materiel bureautique')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type_maintenance')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_debut')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_fin')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('responsable')
                    ->searchable(),
                Tables\Columns\TextColumn::make('statut'),
                Tables\Columns\TextColumn::make('cout_total')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaintenances::route('/'),
            'create' => Pages\CreateMaintenance::route('/create'),
            'edit' => Pages\EditMaintenance::route('/{record}/edit'),
        ];
    }
}
