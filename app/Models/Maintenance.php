<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable =[
        'vehicule_id',
        'material_id',
        'inventory_id',
        'type_maintenance',
        'date_debut',
        'date_fin',
        'responsable',
        'statut',
        'probleme_detecte',
        'action_effectuee',
        'cout_total'

    ];

    public function vehicule()
{
    return $this->belongsTo(Vehicule::class);
}

public function material()
{
    return $this->belongsTo(Material::class);
}

public function inventory()
{
    return $this->belongsTo(Inventory::class);
}

}


