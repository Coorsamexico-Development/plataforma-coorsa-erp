<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class activosItem extends Model
{
    use HasFactory;

    public function tipoActivo ()
    {
       return $this->belongsTo('App\tipos_activo');
    }
}
