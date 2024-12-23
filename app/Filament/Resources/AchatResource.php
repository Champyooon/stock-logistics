<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AchatResource\Pages;
use App\Filament\Resources\AchatResource\RelationManagers;
use App\Models\Achat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AchatResource extends Resource
{
    protected static ?string $model = Achat::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Achat & Logistique';
    protected static ?string $navigationLabel = 'Achat';
    protected static ?string $modelLabel = 'Achat';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('supplier_id')
                    ->relationship(name:'supplier', titleAttribute:'name')
                    //->searchable() Rechercher la categorie au lieu de la choisir
                    //->preload()
                    ->label('Fournisseurs')
                    ->required(),
                Forms\Components\TextInput::make('item_name')
                    ->required()
                    ->label('Désignation')
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('price')
                    ->label('Prix')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('XOF'),
                Forms\Components\TextInput::make('quantity')
                    ->label('Quantité')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\Select::make('statut')
                            ->label('Statut')
                            ->options([
                                'Valide' => 'Valide',
                                'Défaillant' => 'Défaillant',
                            ])
                            ->required()
                            ->default('Valide'),
                Forms\Components\DatePicker::make('date_added')
                    ->label("Date d'ajout")
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('supplier.name')
                    ->label('Fournisseurs')
                    ->numeric()
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
                    Tables\Columns\TextColumn::make('statut')
                ->sortable()
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
                Tables\Actions\EditAction::make()
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
            'index' => Pages\ListAchats::route('/'),
            'create' => Pages\CreateAchat::route('/create'),
            'edit' => Pages\EditAchat::route('/{record}/edit'),
        ];
    }
}
