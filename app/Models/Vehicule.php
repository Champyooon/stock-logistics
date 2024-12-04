<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicule extends Model
{
    use HasFactory;

    protected $fillable =[
        'brand',
        'model',
        'license_plate',
        'type',
        'year_of_manufacture',
        'mileage',
        'status',
        'date_added'
       ];
}
