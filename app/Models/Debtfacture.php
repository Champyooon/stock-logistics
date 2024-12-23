<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Debtfacture extends Model
{
    use HasFactory;

    protected $fillable =[
        'client_id',
        'invoice_id',
        'date_invoice',
        'total_price'
    ];


public function getTotalPriceAttribute(): float
{
    $productsTotal = $this->debtinvoiceItems->sum(fn ($item) => $item->sub_total ?? 0);
    $servicesTotal = $this->serviceItems->sum(fn ($item) => $item->sub_total ?? 0);

    return $productsTotal + $servicesTotal;
}

// Dans le modèle Invoice
protected static function booted()
{
    static::saving(function ($invoice) {
        // Calculez le total des produits et des prestations
        $productsTotal = $invoice->debtinvoiceItems->sum(fn ($item) => $item->sub_total ?? 0);
        $servicesTotal = $invoice->serviceItems->sum(fn ($item) => $item->sub_total ?? 0);

        // Enregistrez le montant total dans la colonne `total_price`
        $invoice->total_price = $productsTotal + $servicesTotal;
    });
}
   public function client()
   {
       return $this->belongsTo(Client::class);
   }


   public function invoice()
   {
       return $this->belongsTo(Invoice::class);
   }
   public function debtinvoiceItems()
   {
       return $this->hasMany(Debtinvoiceitem::class);
   }

   public function serviceItems()
   {
       return $this->hasMany(ServiceItem::class);
   }

}
