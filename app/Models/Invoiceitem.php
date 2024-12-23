<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoiceitem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'product_id',
        'product_price',
        'product_quantity',
        'sub_total',
    ];

    // Relation avec Invoice
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // Relation avec Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relation avec Debtfacture
    public function debtfacture()
    {
        return $this->belongsTo(Debtfacture::class);
    }
}
