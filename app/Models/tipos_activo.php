<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tipos_activo extends Model
{
    use HasFactory;


    protected $fillable = [
        'nombre',
        'imagen'
    ];

    public function activos_items () 
    {
        return $this->hasMany(activosItem::class, 'tipo_activo');
    }

}
