<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentosMe extends Model
{
    use HasFactory;

    protected $fillable = 
    ['user_id',
     'proceso_id',
     'documento',
     'mes',
     'portada'
    ];
}
