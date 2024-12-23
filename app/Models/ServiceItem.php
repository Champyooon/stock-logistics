<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceItem extends Model
{
    use HasFactory;

    protected $fillable =[
        'invoice_id',
        'prestation_id',
        'quantity',
        'price',
        'sub_total'

    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

        public function prestation()
        {
            return $this->belongsTo(Prestation::class);
        }
}
