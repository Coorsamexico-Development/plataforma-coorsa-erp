<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlantillasAutorizada extends Model
{
    use HasFactory;


    protected $fillable = [
        'puesto_id',
        'ubicacione_id',
        'cantidad',
    ];
}
