<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Areas_padres_hijos extends Model
{
    use HasFactory;
    protected $fillable = [
        'areas_id_padre',
        'areas_id_hijo',
        'activo',
    ];
}
