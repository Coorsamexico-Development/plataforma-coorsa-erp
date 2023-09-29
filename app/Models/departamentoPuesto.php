<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class departamentoPuesto extends Model
{
    use HasFactory;

    protected $fillable = [
        'departamento_id',
        'puesto_id',
        'areas_id',
        'plantilla_auth',
        'activo'
    ];
    public function relaciones()
    {
        return $this->hasMany(Padres_hijos::class, 'departamento_puestos_id_padre');
    }
}
