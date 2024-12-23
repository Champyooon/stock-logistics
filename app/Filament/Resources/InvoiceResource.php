<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Prestation; // Vérifiez que ce modèle est bien importé
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Facture';
    protected static ?string $modelLabel = 'Facture';
    protected static ?string $navigationGroup = 'Comptabilité & Trésorerie';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Établissement de la facture
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\TextInput::make('num_invoice')
                                    ->required()
                                    ->label('Numéro de facture'),
                                Forms\Components\DatePicker::make('date_invoice')
                                    ->default(now())
                                    ->required()
                                    ->label('Date de la facture'),
                                Forms\Components\Select::make('client_id')
                                    ->relationship('client', 'company_name')
                                    ->required()
                                    ->label('Client'),
                            ])
                            ->columns(3) // Répartit les champs en 3 colonnes
                            ->columnSpan('full'), // Utilise toute la largeur
                    ])
                    ->columnSpan('full'), // Le groupe occupe toute la largeur

                // Répétiteur pour les produits
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('Produits'),
                        Forms\Components\Repeater::make('invoiceItems')
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
                                    ->dehydrated(false)
                                    ->numeric(),
                                Forms\Components\TextInput::make('sub_total')
                                    ->label('Sous-total')
                                    ->required()
                                    ->readOnly()
                                    ->numeric(),
                            ])
                            ->columns(4) // 4 colonnes pour chaque élément du répéteur
                            ->defaultItems(1)
                            ->columnSpan('full'), // Le répéteur occupe toute la largeur
                    ])
                    ->columnSpan('full'), // La carte occupe toute la largeur

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
                                        $set('price', (float) $prestation->price); // Conversion en float
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
                                $price = (float) $get('price') ?? 0; // Conversion en float
                                $quantity = (float) $get('quantity') ?? 0; // Conversion en float
                                $set('sub_total', $price * $quantity);
                            }),
                        Forms\Components\TextInput::make('price')
                            ->label('Prix unitaire')
                            ->disabled()
                            ->dehydrated(false)
                            ->numeric(),
                        Forms\Components\TextInput::make('sub_total')
                            ->label('Sous-total')
                            ->readOnly()
                            ->numeric(),
                    ])
                    ->columns(4) // 4 colonnes pour chaque élément
                    ->defaultItems(1)
                    ->columnSpan('full'),
                 // Le répéteur occupe toute la largeur

                // Section Montant Total
        Forms\Components\Section::make()
        ->schema([
            Forms\Components\Placeholder::make('Montant total')
                ->content(fn (callable $get) =>
                    number_format(
                        collect($get('invoiceItems') ?? [])
                            ->sum(fn ($item) => $item['sub_total'] ?? 0)
                        +
                        collect($get('serviceItems') ?? [])
                            ->sum(fn ($item) => $item['sub_total'] ?? 0),
                        2
                    ) . ' F CFA'
                ) ->extraAttributes([
                                'style' => 'font-size: 1.5rem; font-weight: bold; text-align: center',
                            ]),
                    ])
                    ->columns(1) // Section pleine largeur
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('client.company_name')->sortable(),
                Tables\Columns\TextColumn::make('num_invoice')->searchable()
                ->label('Numéro Facture'),
                Tables\Columns\TextColumn::make('date_invoice')->date()->sortable()
                ->label("Date d'émission"),
                Tables\Columns\TextColumn::make('total_price')->sortable()->formatStateUsing(fn ($state) => number_format($state, 2) . ' F CFA') // Formatte le montant
                ->label('Montant Total')
                ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('Voir'),
                Tables\Actions\EditAction::make()->label('Modifier'),
                Tables\Actions\DeleteAction::make()->label('Supprimer'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }


    public static function getRelations(): array
    {
        return [];
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
