<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DebtfactureResource\Pages;
use App\Filament\Resources\DebtfactureResource\RelationManagers;
use App\Models\Debtfacture;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DebtfactureResource extends Resource
{
    protected static ?string $model = Debtfacture::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Établissement de la facture
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Select::make('invoice_id')
                                    ->relationship('invoice', 'num_invoice')
                                    ->label('Numéro de Facture')
                                    ->required()
                                    ->reactive() // Rend le champ réactif
                        ->afterStateUpdated(function (callable $get, callable $set) {
                            // Récupérer la facture sélectionnée
                            $invoiceId = $get('invoice_id');
                            if ($invoiceId) {
                                $invoice = \App\Models\Invoice::find($invoiceId);
                                if ($invoice && $invoice->client) {
                                    // Met à jour le champ client_id
                                    $set('client_id', $invoice->client->company_name);
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
                                ->disabled() // Empêche l'édition
                                ->dehydrated(false), // Ne pas inclure dans les données envoyées
                            ])
                            ->columns(['sm' => 3]),

                        // Répétiteur pour les produits
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('Produits'),

                                Forms\Components\Repeater::make('invoiceItems')
                                    ->label('Produit / Prestation')
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
                                            })
                                            ->columnSpan(['md' => 3]),

                                        Forms\Components\TextInput::make('product_quantity')
                                            ->label('Quantité')
                                            ->numeric()
                                            ->default(1)
                                            ->reactive()
                                            ->required()
                                            ->columnSpan(['md' => 1])
                                            ->afterStateUpdated(function (callable $get, callable $set) {
                                                $price = $get('price') ?? 0;
                                                $quantity = $get('product_quantity') ?? 1;
                                                $set('sub_total', $quantity * $price);
                                            }),

                                        Forms\Components\TextInput::make('price')
                                            ->label('Prix unitaire')
                                            ->disabled()
                                            ->dehydrated(false)
                                            ->numeric()
                                            ->columnSpan(['md' => 3]),

                                        Forms\Components\TextInput::make('sub_total')
                                            ->label('Sous-total')
                                            ->required()
                                            ->readOnly()
                                            ->numeric()
                                            ->columnSpan(['md' => 3]),
                                    ])
                                    ->defaultItems(1)
                                    ->columns(['md' => 10])
                                    ->columnSpan('full')
                                    ->afterStateUpdated(function (callable $get, callable $set) {
                                        // Calculer le total de la facture
                                        $total = collect($get('invoiceItems') ?? [])
                                            ->sum(fn ($item) => $item['sub_total'] ?? 0);
                                        $set('total_price', $total);
                                    }),
                            ]),
                    ])
                    ->columnSpan('full'),

                // Section Total Price
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Placeholder::make('Montant total')
                            ->content(fn (callable $get) => number_format(
                                collect($get('invoiceItems') ?? [])
                                    ->sum(fn ($item) => $item['sub_total'] ?? 0),
                                2
                            ).' F CFA')
                            ->extraAttributes([
                                'style' => 'font-size: 1.5rem; font-weight: bold; text-align: center',
                            ]),
                    ])
                    ->columns(['sm' => 1])
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.company_name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice.num_invoice')
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_invoice')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_price')
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
            'index' => Pages\ListDebtfactures::route('/'),
            'create' => Pages\CreateDebtfacture::route('/create'),
            'edit' => Pages\EditDebtfacture::route('/{record}/edit'),
        ];
    }
}
