<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoryResource\Pages;
use App\Filament\Resources\InventoryResource\RelationManagers;
use App\Models\Inventory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoryResource extends Resource
{
    protected static ?string $model = Inventory::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box-arrow-down';
    protected static ?string $navigationLabel = 'Inventaire';
    protected static ?string $modelLabel = 'Inventaire';
    protected static ?string $navigationGroup = 'Achat & Logistique';
    protected static ?int $navigationSort = 2;
    protected static array $statusees =[
        'Disponible'=>'Disponible',
        'Indisponible'=>'Indisponible',
        'En Maintenance'=>'En Maintenance'
    ];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('category_id')
                    ->relationship(name:'category', titleAttribute:'name')
                    //->searchable() Rechercher la categorie au lieu de la choisir
                    //->preload()
                    ->label('Catégorie')
                    ->required(),
                Forms\Components\TextInput::make('item_name')
                    ->label('Désignation')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('brand')
                    ->label('Marque')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('quantity')
                    ->label('Quantité')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\DatePicker::make('date_added')
                    ->label("Date d'ajout")
                    ->required(),
                Forms\Components\TextInput::make('location')
                    ->label('Emplacement')
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->label('Statut')
                    ->options(self::$statusees)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Catégorie')
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_name')
                     ->label('Désignation')
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand')
                    ->label('Marque')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantité')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_added')
                    ->label("Date d'ajout")
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('location')
                    ->label('Emplacement')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Statut'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->defaultSort('category.name')
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
            'index' => Pages\ListInventories::route('/'),
            'create' => Pages\CreateInventory::route('/create'),
            'view' => Pages\ViewInventory::route('/{record}'),
            'edit' => Pages\EditInventory::route('/{record}/edit'),
        ];
    }
}
