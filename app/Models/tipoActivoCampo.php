<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipoActivoCampo extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_activo_id',
        'campo',
        'principal',
        'tipo_input_id'
    ];
}
