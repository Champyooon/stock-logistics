<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Achat extends Model
{
    use HasFactory;

    protected $fillable =[
        'supplier_id',
        'item_name',
        'description',
        'price',
        'quantity',
        'date_added'
    ];

    public function supplier():BelongsTo
   {
     return $this->belongsTo(Supplier::class);
   }
}
