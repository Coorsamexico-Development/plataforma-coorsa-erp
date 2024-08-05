<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudVacaciones extends Model
{
    use HasFactory;

    protected $fillable = [
        'proceso_user_id',
        'user_id',
        'fechas',
        'dias_solicitados',
        'activo',
    ];
}