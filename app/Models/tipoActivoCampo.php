<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class tipoActivoCampo extends Model
{
    use HasFactory;


    protected $fillable = [
        'tipo_activo_id',
        'campo',
        'principal',
        'tipo_input_id',
        'tabla_id'
    ];


    protected $appends = [
        'DistintosValores',
    ];

    public function tipoInput ()
    {
        return $this->hasOne(TipoInput::class, 'tipo_input_id');
    }


    public function getDistintosValoresAttribute()
    {
        return valorCampoActivo::select('valor_campo_activos.*')
        ->where('valor_campo_activos.tipo_activo_campo_id','=',1)
        ->get();
    }
    
}
