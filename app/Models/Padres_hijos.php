<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Padres_hijos extends Model
{
    use HasFactory;

    protected $fillable = [
        'departamento_puestos_id_padre',
        'departamento_puestos_id_hijo',
        'activo',
    ];
}
