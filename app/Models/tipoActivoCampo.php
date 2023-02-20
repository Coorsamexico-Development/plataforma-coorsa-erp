<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    public function tipoInput ()
    {
        return $this->hasOne(TipoInput::class, 'tipo_input_id');
    }

    
}
