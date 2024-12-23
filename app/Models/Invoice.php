<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'num_invoice',
        'date_invoice',
        'total_price',
        'observation',
    ];

    // Calcul du total des produits et des prestations avant sauvegarde
    public function getTotalPriceAttribute(): float
    {
        $productsTotal = $this->invoiceItems->sum(fn ($item) => $item->sub_total ?? 0);
        $servicesTotal = $this->serviceItems->sum(fn ($item) => $item->sub_total ?? 0);

        return $productsTotal + $servicesTotal;
    }

    // Dans le modèle Invoice, calculer et enregistrer le total avant la sauvegarde
    protected static function booted()
    {
        static::saving(function ($invoice) {
            // Calculez le total des produits et des prestations
            $productsTotal = $invoice->invoiceItems->sum(fn ($item) => $item->sub_total ?? 0);
            $servicesTotal = $invoice->serviceItems->sum(fn ($item) => $item->sub_total ?? 0);

            // Enregistrez le montant total dans la colonne `total_price`
            $invoice->total_price = $productsTotal + $servicesTotal;
        });
    }

    // Relation avec le Client
    public function client(): BelongsTo
    { 
        return $this->belongsTo(Client::class);
    }

    // Relation avec les éléments de facture (InvoiceItems)
    public function invoiceItems()
    {
        return $this->hasMany(Invoiceitem::class);
    }

    // Relation avec les éléments de service (ServiceItems)
    public function serviceItems()
    {
        return $this->hasMany(ServiceItem::class);
    }
}
