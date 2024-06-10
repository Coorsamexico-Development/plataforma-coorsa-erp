<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CiDatas extends Model
{
    use HasFactory;

    protected $fillable = [
        'año_id',
        'mes_id',
        'atributo_id',
        'value',
        'seccion_id',
    ];
}
