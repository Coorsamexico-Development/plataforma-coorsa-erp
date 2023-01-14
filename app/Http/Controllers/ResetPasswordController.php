<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Helpers\SendResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ResetPasswordController extends Controller
{

    protected $table = null;

    public function __construct()
    {
        $this->table = config('auth.passwords.users.table');
    }

    public function create(Request $request)
    {
        return Inertia::render('Auth/ResetPasswordFirst', [
            'token' => $request->route('token'),
            'email' => $request->email,
        ]);
    }

    /**
     * Send email of user to initial session
     */
    public function store(User $user)
    {

        $users = User::select('users.email')
        ->where('users.activo','=',1)
        ->orderBy('users.id')
        ->get();


        $userss = [];

        for ($i=0; $i < count($users) ; $i++) 
        { 
            $usuario = $users[$i];
            array_push($userss, $usuario);
        }


        //return $userss;

        for ($x=0; $x < count($userss) ; $x++) 
        { 
            $usuario2 = $userss[$x];
            $sendRestPassword = new SendResetPassword();
            $message = $sendRestPassword->send($usuario2);
        }

      // $sendRestPassword = new SendResetPassword();
       $message = $sendRestPassword->send($user);
        return redirect()->back()->with([
            'message' => __($message)
          ]);
    }


    function removeProperties($mObject, $mProperties){
        /*
            Recorremos el array de propiedades a eliminar
        */
        foreach ($mProperties as $property){
            /*
                Eliminamos la propiedad con unset
                No hace falta verificar si existe o no
                porque unset no devuelve nada
                observa que en el array puse a propósito una propiedad 'fake'
                y ningún mensaje ni error es levantado
            */
            unset($mObject->$property);
        }
    }

    public function update(Request $request)
    {

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);
        $user =  User::firstWhere('email', '=', $request->email);
        if ($user === null) {
            return back()->withErrors([
                'email' => __('passwords.user'),
            ]);
        }
        $record = (array) DB::table($this->table)->where(
            'email',
            $user->getEmailForPasswordReset()
        )->first();
        if (empty($record)) {
            return back()->withErrors([
                'email' => __('passwords.token'),
            ]);
        }

        // Check token and if correct save password and login de user
        if (Hash::check($request->token, $record['token'])) {

            $user->forceFill([
                'password' => Hash::make($request->password)
            ])->setRememberToken(Str::random(60));

            $user->saveQuietly();
            // delete token token, el token ya no va ser valido una ves utilizado
            DB::table($this->table)->where('email', $user->getEmailForPasswordReset())
                ->delete();
            Auth::login($user);
            $request->session()->regenerate();
            // el intent no es necesario
            return redirect()->route('dashboard');
        }
        return back()->withErrors([
            'email' => __('passwords.token'),
        ])->onlyInput('email');
    }
}
