<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class evidenciasActivo extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'tipo_evidencia_id',
        'user_id',
        'imagen',
        'activo_id'
    ];
}
