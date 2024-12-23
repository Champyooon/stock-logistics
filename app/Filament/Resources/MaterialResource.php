<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaterialResource\Pages;
use App\Filament\Resources\MaterialResource\RelationManagers;
use App\Models\Material;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MaterialResource extends Resource
{
    protected static ?string $model = Material::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationLabel = 'Matériel';
    protected static ?string $modelLabel = 'Matériel';
    protected static ?string $navigationGroup = 'Achat & Logistique';
    protected static ?int $navigationSort = 3;

    protected static array $statuseees =[
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
                    ->searchable() //Rechercher la categorie au lieu de la choisir
                    ->preload()
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
                Forms\Components\Select::make('status')
                ->label('Statut')
                ->options(self::$statuseees)
                    ->required(),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Catégorie')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('item_name')
                    ->label('Désignation')
                    ->searchable(),
                Tables\Columns\TextColumn::make('brand')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantité')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_added')
                    ->date()
                    ->label("Date d'ajout")
                    ->sortable(),
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
            'index' => Pages\ListMaterials::route('/'),
            'create' => Pages\CreateMaterial::route('/create'),
            'view' => Pages\ViewMaterial::route('/{record}'),
            'edit' => Pages\EditMaterial::route('/{record}/edit'),
        ];
    }
}
