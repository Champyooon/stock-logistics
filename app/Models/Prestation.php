<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prestation extends Model
{
    use HasFactory;

    protected $fillable =[
        'department_id',
        'designation',
        'nreference',
        'price'

    ];
    public function department():BelongsTo
    {
      return $this->belongsTo(Department::class);
    }
}
