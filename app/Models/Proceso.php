<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proceso extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'logo',
        'departamento_auditoria_id',
        'activo'
    ];

    public function rubros () 
    {
        return $this -> hasMany(Rubro::class, 'proceso_id');
    }
}
