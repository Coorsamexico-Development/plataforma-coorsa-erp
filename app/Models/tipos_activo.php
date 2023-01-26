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


    protected $appends = [
        'camposInput'
    ];

    public function activos_items () 
    {
        return $this->hasMany(activosItem::class, 'tipo_activo');
    }


    public function getCamposInputAttribute () 
    {
        //return $campos_inputs = [];

       return  tipoActivoCampo::select(
            'tipo_activo_campos.id AS idCampo',
            'tipo_activo_campos.campo AS campo',
            'tipo_activo_campos.principal',
            'tipo_inputs.nombre AS input',
        )
        ->join('tipos_activos','tipo_activo_campos.tipo_activo_id','tipos_activos.id')
        ->join('tipo_inputs','tipo_activo_campos.tipo_input_id','tipo_inputs.id')
        ->where('tipos_activos.id','=',$this->id)
        ->get();
        //return $this->hasMany(tipoActivoCampo::class, 'tipo_activo_id')->hasOne(TipoInput::class, 'tipo_input_id');

        /*
        return tipoActivoCampo::select(
            'tipo_activo_campos.*'
        )
        ->LeftJoin('tipos_activos','tipo_activo_campos.tipo_activo_id','tipos_activos.nombre')
        ->join('tipo_inputs','tipo_activo_campos.tipo_input_id','tipo_inputs.id')
        ->where('tipos_activos.id','LIKE','%'.$this->id.'%')
        ->get();
        */
    }
}
