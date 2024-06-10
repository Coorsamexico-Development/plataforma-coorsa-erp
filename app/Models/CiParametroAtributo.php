<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CiParametroAtributo extends Model
{
    use HasFactory;

    protected $fillable = [
        'año_id',
        'mes_id',
        'atributo_id',
        'parametro_id',
        'seccion_id',
        'value',
    ];
}
