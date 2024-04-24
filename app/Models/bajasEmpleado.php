<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bajasEmpleado extends Model
{
    use HasFactory;

    protected $fillable = [
        'cat_bajas_empleado_id',
        'empleado_id',
        'fecha_finiquito',
        'fecha_baja',
    ];
}
