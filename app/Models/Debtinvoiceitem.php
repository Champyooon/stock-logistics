<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Debtinvoiceitem extends Model
{
    use HasFactory;

    protected $fillable =[
        'debtfacture_id',
        'product_id',
        'product_price',
        'product_quantity',
        'sub_total'

    ];

    public function debtfacture()
    {
        return $this->belongsTo(Debtfacture::class);
    }

        public function product()
        {
            return $this->belongsTo(Product::class);
        }
}
