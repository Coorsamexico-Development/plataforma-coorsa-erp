<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalificacionMes extends Model
{
    use HasFactory;

    protected $fillable = [
        'parametro_id',
        'user_id',
        'calificacion'
    ];
}
