<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable =[
        'client_id',
        'num_invoice',
        'date_invoice',
        'designation',
        'quantity',
        'prix_unit',
        'prix_total',
        'observation'
    ];
    public function client():BelongsTo
    {
      return $this->belongsTo(Client::class);
    }
}
