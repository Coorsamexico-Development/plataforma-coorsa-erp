<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Plataforma;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{



    /**
     * Get list of users
     */
    public function list()
    {
        return response()->json(User::select('id')
            ->selectRaw("concat(users.name,IFNULL(concat(' ',users.apellido_paterno), '')) as name")->get());
    }
}
