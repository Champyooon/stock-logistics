<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VenteResource\Pages;
use App\Filament\Resources\VenteResource\RelationManagers;
use App\Models\Vente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VenteResource extends Resource
{
    protected static ?string $model = Vente::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationLabel = 'Vente';
    protected static ?string $modelLabel = 'Vente';
    protected static ?string $navigationGroup = 'Comptabilité & Trésorerie';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('client_id')
                    ->relationship(name:'client', titleAttribute:'company_name')
                    //->searchable() Rechercher la categorie au lieu de la choisir
                    //->preload()
                    ->required(),
                    Forms\Components\Select::make('product_id')
                    ->relationship(name:'product', titleAttribute:'item_name')
                    //->searchable() Rechercher la categorie au lieu de la choisir
                    //->preload()
                    ->required(),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->default(1)
                    ->prefix('F CFA'),
                Forms\Components\TextInput::make('totalprice')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\DatePicker::make('date_vente')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),
                Tables\Columns\TextColumn::make('totalprice')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_vente')
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
            'index' => Pages\ListVentes::route('/'),
            'create' => Pages\CreateVente::route('/create'),
            'edit' => Pages\EditVente::route('/{record}/edit'),
        ];
    }
}
