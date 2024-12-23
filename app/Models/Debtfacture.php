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


// Utilisation de booted() pour recalculer le total avant la sauvegarde
protected static function booted()
{
    static::saving(function ($invoice) {
        // Charge les éléments associés à la facture (debtinvoiceItems)
        $invoice->load('debtinvoiceItems');

        // Calcul du total des sous-totaux
        $total = $invoice->debtinvoiceItems->sum('sub_total');

        // Mise à jour du total dans la facture
        $invoice->total_price = $total;
    });
}
   public function client()
   {
       return $this->belongsTo(Client::class);
   }

   public function invoiceItems()
{
    return $this->hasMany(Debtinvoiceitem::class, 'debtfacture_id');
}


   public function invoice()
   {
       return $this->belongsTo(Invoice::class);
   }

   public function debtinvoiceItems()
{
    return $this->hasMany(Debtinvoiceitem::class);
}
}
