<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class activosItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'tipo_activo',
        'fecha',
        'status',
    ];

    public function tipoActivo ()
    {
       return $this->belongsTo('App\tipos_activo');
    }

    public function valor_campos_activos ()
    {
        return $this->hasMany(valorCampoActivo::class,'activo_id');
    }
}
