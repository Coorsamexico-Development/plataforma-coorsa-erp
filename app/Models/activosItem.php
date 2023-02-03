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
        'status_activo_id'
    ];


    public function tipoActivo ()
    {
       return $this->belongsTo('App\tipos_activo');
    }

    public function valor_campos_activos ()
    {
        return $this->hasMany(valorCampoActivo::class,'activo_id');
    }

    public function activos_empleados()
    {
        return 
        $this->hasMany(activos_empleado::class, 'activo_id');
        /*
        ->join('users', 'activos_empleados.empleado_id','users.id')
        ->join('empleados_puestos','empleados_puestos.empleado_id','users.id');
        
        /*

     
    
        ->where('activos_empleados.status','=',1);

        /*
        ->join('empleados_puestos','empleados_puestos.empleado_id','users.id')
        ->join('puestos', 'empleados_puestos.puesto_id', 'puestos.id')
        ->join('cecos','empleados_puestos.departamento_id','cecos.id')
        ->join('ubicaciones','cecos.ubicacione_id','ubicaciones.id')
        */
       
    }

    public function evidencias_activo ()
    {
        return $this->hasMany(evidenciasActivo::class, 'activo_id');
    }

}
