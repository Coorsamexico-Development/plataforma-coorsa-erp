<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentosCalificacionMes extends Model
{
    use HasFactory;

    protected $table = 'documentos_calificacion_mes';

    protected $fillable = [
        'user_id',
        'departamento_auditoria_id',
        'documento_url',
        'calificacion',
        'mes',
        'fecha_creacion'
    ];
}
