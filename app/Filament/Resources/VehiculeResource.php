<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehiculeResource\Pages;
use App\Filament\Resources\VehiculeResource\RelationManagers;
use App\Models\Vehicule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VehiculeResource extends Resource
{
    protected static ?string $model = Vehicule::class;

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    protected static ?string $navigationLabel = 'Véhicule';
    protected static ?string $modelLabel = 'Véhicule';
    protected static ?string $navigationGroup = 'Achat & Logistique';
    protected static ?int $navigationSort = 4;
    protected static array $statusees =[
        'Disponible'=>'Disponible',
        'Indisponible'=>'Indisponible',
        'En Maintenance'=>'En Maintenance'

    ];
    protected static array $type =[
        'Voiture'=>'Voiture',
        'Fourgonette'=>'Fourgonette',
        'Moto'=>'Moto',
        '4x4'=>'4x4'

    ];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('brand')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('model')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('license_plate')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options(self::$type)
                    ->required(),
                Forms\Components\TextInput::make('year_of_manufacture')
                    ->required(),
                Forms\Components\TextInput::make('mileage')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('status')
                    ->options(self::$statusees)
                    ->required(),
                Forms\Components\DatePicker::make('date_added')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('brand')
                    ->label('Marque')
                    ->searchable(),
                Tables\Columns\TextColumn::make('model')
                    ->label('Modèle')
                    ->searchable(),
                Tables\Columns\TextColumn::make('license_plate')
                    ->label("Numero d'immatriculation")
                    ->searchable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('year_of_manufacture')
                    ->label('Année'),
                Tables\Columns\TextColumn::make('mileage')
                    ->label('Kilométrage')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Statut'),
                Tables\Columns\TextColumn::make('date_added')
                    ->label("Date d'ajout")
                    ->date()
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListVehicules::route('/'),
            'create' => Pages\CreateVehicule::route('/create'),
            'view' => Pages\ViewVehicule::route('/{record}'),
            'edit' => Pages\EditVehicule::route('/{record}/edit'),
        ];
    }
}
