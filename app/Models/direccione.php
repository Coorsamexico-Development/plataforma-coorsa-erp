<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class direccione extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'direccion_localidade_id',
        'calle',
        'numero',
        'colonia',
        'codigo_postal',
        'lote',
        'manzana',
        'activo'
    ];
}
