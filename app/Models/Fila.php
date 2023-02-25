<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fila extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_activo_campo_id',
        'activos_item_id',
        'fila_id'
    ];

    public function campos()
    {
        return $this->hasMany(valorCampoActivo::class , 'fila_id');
    }
}
