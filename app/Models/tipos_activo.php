<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class tipos_activo extends Model
{
    use HasFactory;


    protected $fillable = [
        'nombre',
        'imagen'
    ];


    protected $appends = [
        'camposInput',
        'camposAllInput',
        'totalUso',
        'totalLibre',
        'totalBaja'
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
        ->where('tipo_activo_campos.principal','=',1)
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

    public function getCamposAllInputAttribute () 
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


    public function getTotalUsoAttribute () 
    {
      return activosItem::select(
         DB::raw('COUNT(activos_items.id) AS Uso')
       )
       ->join('tipos_activos','activos_items.tipo_activo','tipos_activos.id')
       ->where('tipo_activo','=', $this->id)
       ->where('status_activo_id','=',1)
       ->get();
    }
    
    public function getTotalLibreAttribute () 
    {
      return  activosItem::select(
            DB::raw('COUNT(activos_items.id) AS Libre')
          )
          ->join('tipos_activos','activos_items.tipo_activo','tipos_activos.id')
          ->where('tipo_activo','=', $this->id)
          ->where('status_activo_id','=',2)
          ->get();
    }

    public function getTotalBajaAttribute () 
    {
      return activosItem::select(
            DB::raw('COUNT(activos_items.id) AS Baja')
          )
          ->join('tipos_activos','activos_items.tipo_activo','tipos_activos.id')
          ->where('tipo_activo','=', $this->id)
          ->where('status_activo_id','=',3)
          ->get();
    }
}
