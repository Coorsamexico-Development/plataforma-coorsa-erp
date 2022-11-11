<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartamentosAuditoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'logo'
    ];


    public function documentosCalificacionesMes()
    {
        return $this->hasMany(DocumentosCalificacionMes::class, 'departamento_auditoria_id');
    }
}
