<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vente extends Model
{
    use HasFactory;

    protected $fillable =[
        'client_id',
        'product_id',
        'quantity',
        'price',
        'totalprice',
        'date_vente'

    ];

    public function client():BelongsTo
    {
      return $this->belongsTo(Client::class);
    }
    public function product():BelongsTo
    {
      return $this->belongsTo(Product::class);
    }
}
