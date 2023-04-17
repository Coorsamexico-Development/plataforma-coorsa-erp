<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Inertia\Inertia;
use App\Models\Permission;
use App\Models\Plataforma;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        request()->validate([
            'direction' => 'in:asc,desc'
        ]);
        $roles = Role::select('roles.*');
        $permissons = Permission::select('permissions.*');
        $plataformas = Plataforma::select('plataformas.*');

        if (request()->has('plataforma_id')) {
            $permissons->where('plataforma_id', '=', request('plataforma_id'));
        }
        if (request()->has('search')) {
            $search = '%' . strtr(request('search'), array("'" => "\\'", "%" => "\\%")) . '%';
            $roles->where('roles.nombre', 'like',  $search);
        }

        if (request()->has('field')) {
            $roles->orderBy(request('field'), request('direction'));
        } else {
            $roles->orderBy('roles.created_at', 'desc');
        }
        $nominas = DB::table('nominas_empleados')->where('empleado_id', auth()->user()->id)->orderByDesc('fecha_doc')->orderByDesc('periodo')->paginate(5);

        return Inertia::render('Roles/RolesIndex', [
            'laravelRoles' => fn () =>  $roles->paginate(20),
            'laravelPermissions' => fn () => $permissons->get(),
            'laravelPlataformas' => fn () => $plataformas->get(),
            'filters' => request(['search', 'field', 'direction', 'plataforma_id']),
            'nominas' => $nominas
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $newRole = $request->validated();

        Role::create($newRole);
        return redirect()->back();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, Role $role)
    {
        $newRole = $request->validated();

        $role->update($newRole);
        return redirect()->back();
    }

    public function permissions(Role $role)
    {
        $permissions = $role->permissions()
            ->select('permissions.id')
            ->get();
        return response()->json([
            'permissonId' => $permissions->pluck('id')
        ]);
    }

    public function setPermission(Role $role, Request $request)
    {
        $request->validate([
            'permission_id' => ['required', 'exists:permissions,id'],
            'checked' => ['required', 'boolean']
        ]);
        if ($request->checked) {
            $role->permissions()->attach([$request->permission_id]);
        } else {
            $role->permissions()->detach([$request->permission_id]);
        }
        return response()->json([
            'message' => 'ok'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        //
    }
}
