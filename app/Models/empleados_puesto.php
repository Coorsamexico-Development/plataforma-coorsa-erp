<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class empleados_puesto extends Model
{
    use HasFactory;
    protected $fillable = 
    ['departamento_id',
     'puesto_id',
     'empleado_id'
    ];
}
