<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ceco extends Model
{
    use HasFactory;


    protected $fillable = [
        'nombre',
        'cliente_id',
        'ubicacione_id',
        'activo_erp',
        'descripcion',
    ];

    protected $casts = [
        'activo_erp' => 'boolean'
    ];

    public function puestos()
    {
        return $this->belongsToMany(puesto::class, 'departamento_puestos', 'departamento_id', 'puesto_id');
    }
}
