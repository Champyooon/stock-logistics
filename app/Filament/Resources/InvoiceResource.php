<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-paper-clip';
    protected static ?string $navigationLabel = 'Facture';
    protected static ?string $modelLabel = 'Facture';
    protected static ?string $navigationGroup = 'Comptabilité & Trésorerie';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('client_id')
                    ->relationship(name:'client', titleAttribute:'company_name')
                    //->searchable() Rechercher la categorie au lieu de la choisir
                    //->preload()
                    ->required(),
                Forms\Components\TextInput::make('num_invoice')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('date_invoice')
                    ->required(),
                Forms\Components\TextInput::make('designation')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('prix_unit')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('XOF'),
                Forms\Components\TextInput::make('prix_total')
                    ->numeric()
                    ->default(0)
                    ->prefix('XOF'),
                Forms\Components\TextInput::make('observation')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.company_name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('num_invoice')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_invoice')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('designation')
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('prix_unit')
                ->money('XOF')
                ->sortable(),
                Tables\Columns\TextColumn::make('prix_total')
                ->formatStateUsing(function ($record) {
                    return number_format($record->prix_unit * $record->quantity) . ' F CFA';
                })
                ->sortable(),
                Tables\Columns\TextColumn::make('observation')
                    ->searchable(),
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
                Tables\Actions\DeleteAction::make()
                    ->successNotification(
            Notification::make()
                    ->success()
                    ->title('Suppression réussie')
                    ->body("La facture a été supprimée avec succès."))
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
