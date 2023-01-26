<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class valorCampoActivo extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'valor',
        'tipo_activo_campo_id',
        'activo_id',
    ];
}
