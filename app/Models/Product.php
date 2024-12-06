<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable =[
        'department_id',
        'item_name',
        'description',
        'price',
        'quantity',
        'date_added'

    ];
    public function department():BelongsTo
    {
      return $this->belongsTo(Department::class);
    }
}
