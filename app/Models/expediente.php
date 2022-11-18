<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expediente extends Model
{
    use HasFactory;

    protected $fillable = [
        'ruta',
        'cat_tipos_documento_id',
        'empleado_id'
    ];
}
