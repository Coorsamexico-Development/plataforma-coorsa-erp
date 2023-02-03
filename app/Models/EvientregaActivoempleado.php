<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvientregaActivoempleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'evidencia_entrega_id',
        'activo_empleado_id',
        'foto',
    ];
}
