<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DebtfactureResource\Pages;
use App\Filament\Resources\DebtfactureResource\RelationManagers;
use App\Models\Debtfacture;
use App\Models\Prestation;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DebtfactureResource extends Resource
{
    protected static ?string $model = Debtfacture::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = "Facture d'avoir";
    protected static ?string $modelLabel = "Facture d'avoir";
    protected static ?string $navigationGroup = 'Comptabilité & Trésorerie';
    protected static ?int $navigationSort = 2;
    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Grid::make()
                ->schema([
                    // Section d'établissement de la facture
                    Forms\Components\Select::make('invoice_id')
                        ->relationship('invoice', 'num_invoice')
                        ->label('Numéro de Facture')
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (callable $get, callable $set) {
                            $invoiceId = $get('invoice_id');
                            if ($invoiceId) {
                                $invoice = \App\Models\Invoice::find($invoiceId);
                                if ($invoice && $invoice->client) {
                                    $set('client_id', $invoice->client->company_name);  // Mettez à jour le client ici
                                }
                            } else {
                                $set('client_id', null);
                            }
                        }),
                    Forms\Components\DatePicker::make('date_invoice')
                        ->default(now())
                        ->label("Date de la facture d'avoir")
                        ->required(),
                    Forms\Components\TextInput::make('client_id')
                        ->label('Client associé')
                        ->disabled()
                        ->readOnly(),
                ])
                ->columns(3) // Répartit les champs en 3 colonnes
                ->columnSpan('full'),

                // Section des produits
            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\Repeater::make('debtinvoiceItems')
                        ->relationship()
                        ->schema([
                            Forms\Components\Select::make('product_id')
                                ->label('Produit')
                                ->options(Product::query()->pluck('item_name', 'id'))
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function (callable $get, callable $set) {
                                    $productId = $get('product_id');
                                    if ($productId) {
                                        $product = Product::find($productId);
                                        if ($product) {
                                            $set('price', $product->price);
                                            $set('sub_total', $get('product_quantity') * $product->price);
                                        }
                                    }
                                }),
                            Forms\Components\TextInput::make('product_quantity')
                                ->label('Quantité')
                                ->numeric()
                                ->default(1)
                                ->reactive()
                                ->required()
                                ->afterStateUpdated(function (callable $get, callable $set) {
                                    $price = $get('price') ?? 0;
                                    $quantity = $get('product_quantity') ?? 1;
                                    $set('sub_total', $quantity * $price);
                                }),
                            Forms\Components\TextInput::make('price')
                                ->label('Prix unitaire')
                                ->disabled()
                                ->readOnly()
                                ->numeric(),
                            Forms\Components\TextInput::make('sub_total')
                                ->label('Sous-total')
                                ->required()
                                ->readOnly()
                                ->numeric(),
                        ])
                        ->columns(4)
                        ->defaultItems(1)
                        ->columnSpan('full'),
                ])
                ->columnSpan('full'),

                // Section des prestations
            Forms\Components\Card::make()
                ->schema([
                    Forms\Components\Repeater::make('serviceItems')
                        ->relationship('serviceItems')
                        ->schema([
                            Forms\Components\Select::make('prestation_id')
                                ->label('Prestation')
                                ->options(Prestation::query()->pluck('designation', 'id'))
                                ->required()
                                ->reactive()
                                ->afterStateUpdated(function (callable $get, callable $set) {
                                    $prestationId = $get('prestation_id');
                                    if ($prestationId) {
                                        $prestation = Prestation::find($prestationId);
                                        if ($prestation) {
                                            $set('price', (float) $prestation->price);
                                            $quantity = (float) $get('quantity') ?? 0;
                                            $set('sub_total', $quantity * (float) $prestation->price);
                                        }
                                    }
                                }),
                            Forms\Components\TextInput::make('quantity')
                                ->label('Quantité')
                                ->numeric()
                                ->default(1)
                                ->reactive()
                                ->required()
                                ->afterStateUpdated(function (callable $get, callable $set) {
                                    $price = (float) $get('price') ?? 0;
                                    $quantity = (float) $get('quantity') ?? 0;
                                    $set('sub_total', $price * $quantity);
                                }),
                            Forms\Components\TextInput::make('price')
                                ->label('Prix unitaire')
                                ->disabled()
                                ->readOnly()
                                ->numeric(),
                            Forms\Components\TextInput::make('sub_total')
                                ->label('Sous-total')
                                ->readOnly()
                                ->numeric(),
                        ])
                        ->columns(4)
                        ->defaultItems(1)
                        ->columnSpan('full'),
                ])
                ->columnSpan('full'),

                // Section total
            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\Placeholder::make('Montant total')
                        ->content(fn (callable $get) =>
                            number_format(
                                collect($get('debtinvoiceItems') ?? [])
                                    ->sum(fn ($item) => $item['sub_total'] ?? 0)
                                +
                                collect($get('serviceItems') ?? [])
                                    ->sum(fn ($item) => $item['sub_total'] ?? 0),
                                2
                            ) . ' F CFA')
                        ->extraAttributes([
                            'style' => 'font-size: 1.5rem; font-weight: bold; text-align: center',
                        ]),
                ])
                ->columnSpan('full'),
        ])
        ->columns(1); // Nombre de colonnes dans la grille principale
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //Tables\Columns\TextColumn::make('clients.company_name')
                   // ->label('Client')
                   // ->sortable(),
                Tables\Columns\TextColumn::make('invoice.num_invoice')
                    ->label('Numero de la facture')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_invoice')
                    ->label("Date d'emision")
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Prix total')
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
                Tables\Actions\ViewAction::make()->label('Voir'),
                Tables\Actions\DeleteAction::make()->label('Supprimer'),
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
            'index' => Pages\ListDebtfactures::route('/'),
            'create' => Pages\CreateDebtfacture::route('/create'),
            'edit' => Pages\EditDebtfacture::route('/{record}/edit'),
        ];
    }
}
