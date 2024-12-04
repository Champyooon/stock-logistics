<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Material extends Model
{
   use HasFactory;

   protected $fillable =[
    'category_id',
    'item_name',
    'brand',
    'quantity',
    'date_added',
    'status'
   ];


   public function category():BelongsTo
   {
     return $this->belongsTo(Category::class);
   }
}
