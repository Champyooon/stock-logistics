<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationLabel = 'Produit';
    protected static ?string $modelLabel = 'Produit';
    protected static ?string $navigationGroup = 'Comptabilité & Trésorerie';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('department_id')
                    ->relationship(name:'department', titleAttribute:'name')
                    ->searchable() //Rechercher la categorie au lieu de la choisir
                    //->preload()
                    ->required(),
                Forms\Components\TextInput::make('item_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('F CFA'),
                Forms\Components\TextInput::make('quantity')
                    //->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\DatePicker::make('date_added')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Département')
                    ->sortable(),
                Tables\Columns\TextColumn::make('item_name')
                    ->label('Désignation')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Prix')
                    ->money('XOF')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantité')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_value')
                    ->label('Valeur totale')
                    ->getStateUsing(fn ($record) => number_format(($record->price ?? 0) * ($record->quantity ?? 0), 0, ',', ' ') . ' XOF')
                    ->sortable(),
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
                Tables\Actions\ViewAction::make()
                ->label('Voir'),
                Tables\Actions\EditAction::make()
                ->label('Modifier'),
                Tables\Actions\DeleteAction::make()
                ->label('Supprimer')
                    ->successNotification(
            Notification::make()
                    ->success()
                    ->title('Suppression réussie')
                    ->body("Les informations du produit ont été supprimées avec succès."))
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
