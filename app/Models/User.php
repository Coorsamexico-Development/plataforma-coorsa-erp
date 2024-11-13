<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
        'is_admin',
        // 'expedienteGeneral',
        'contrato',
    ];

    //Se crea el atributo is_admin donde el rol con id 1 es admin
    public function getIsAdminAttribute()
    {
        return $this->role_id === 1; // admin
    }

    //Un usuario solo puede tener 1 rol
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }



    /**
     * Determina si puede authenticarse
     */
    public function canAccess()
    {
        $can = true;

        if (!$this->is_admin) {
            return  $this->role->permissions()->where('permissions.plataforma_id', '=', 2)
                ->where('permissions.is_acceso', 1)->exists();
        }

        return $can;
    }

    public function getCansAttribute()
    {
        $cans = array();
        if ($this->is_admin) {
            $permissions = Permission::select('id', 'nombre')
                ->where('plataforma_id', '=', 2)->get();
        } else {
            $permissions = $this->role->permissions()
                ->where('plataforma_id', '=', 2)->get();
        }
        foreach ($permissions as $permission) {
            $cans[$permission->nombre] =   $this->can($permission->nombre);
        }
        return $cans;
    }

    public function hasPermission(Int $idPermission)
    {
        return $this->role->permissions()->where('permissions.id', $idPermission)->exists();
    }


    public function expedientes()
    {
        return $this->hasMany(expediente::class, 'empleado_id', 'id');
    }


    public function getExpedienteGeneralAttribute()
    {
        return $this->expedientes()->select('id', 'ruta', 'cat_tipos_documento_id')
            ->where('cat_tipos_documento_id', 25)->first();
    }

    public function getContratoAttribute()
    {
        return $this->expedientes()->select('id', 'ruta', 'cat_tipos_documento_id')
            ->where('cat_tipos_documento_id', 26)->first();
    }

    public function puestos()
    {
        return $this->belongsToMany(puesto::class, 'empleados_puestos', 'empleado_id', 'dpto_puesto_id');
    }
    //por ahora no puede ser un attributo
    public function getPuesto()
    {
        return $this->puestos()->where('empleados_puestos.activo', '=', 1)->first();
    }
}
