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
    
        $sendRestPassword = new SendResetPassword();
        $users = User::select('users.*')
        ->where('users.activo','=',1)
        ->get();

        for ($i=0; $i < count($users) ; $i++) 
        { 
            $usuario = $users[$i];
            $message = $sendRestPassword->send($usuario);
        }

        return redirect()->back()->with([
            'message' => __($message)
        ]);
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
