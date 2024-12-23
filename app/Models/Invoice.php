<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable =[
        'client_id',
        'num_invoice',
        'date_invoice',
        'total_price',
        'observation'
    ];

  // Utilisation de booted() pour recalculer le total avant la sauvegarde

  public function getTotalPriceAttribute(): float
{
    $productsTotal = $this->invoiceItems->sum(fn ($item) => $item->sub_total ?? 0);
    $servicesTotal = $this->serviceItems->sum(fn ($item) => $item->sub_total ?? 0);

    return $productsTotal + $servicesTotal;
}

// Dans le modèle Invoice
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


    public function client():BelongsTo
    {
      return $this->belongsTo(Client::class);
    }

    public function invoiceItems()
{
    return $this->hasMany(Invoiceitem::class);
}

public function serviceItems()
{
    return $this->hasMany(ServiceItem::class);
}

}
