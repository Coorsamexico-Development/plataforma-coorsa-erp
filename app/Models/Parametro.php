<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{
    use HasFactory;


    protected $fillable = [
        'proceso_id',
        'user_id',
        'name'
    ];
}
