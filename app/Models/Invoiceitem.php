<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoiceitem extends Model
{
    use HasFactory;

    protected $fillable =[
        'invoice_id',
        'product_id',
        'product_price',
        'product_quantity',
        'sub_total'

    ];

    public function invoice()
{
    return $this->belongsTo(Invoice::class);
}

    public function product()
    {
        return $this->belongsTo(Product::class);
    }



}
