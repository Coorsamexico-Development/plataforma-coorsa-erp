<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Notifications\ResetPasswordNotification;

class SendResetPassword
{

    protected $table = null;
    public function __construct()
    {
        $this->table = config('auth.passwords.users.table');
    }


    /**
     * Send email with Welcome to the plataform and link to rest first password
     * @param User $user
     * @return String
     */
    public function send(User $user)
    {
        $email = $user->getEmailForPasswordReset();
        //Delete all existing reset tokens from the database.
        DB::table($this->table)->where('email', $email)->delete();
        // We will create a new, random token for the user so that we can e-mail them
        // a safe link to the password reset form. Then we will insert a record in
        // the database so that we can verify the token within the actual reset.

        $newToken = hash_hmac('sha256', Str::random(40), config('app.key'));
        $payload = ['email' => $email, 'token' => Hash::make($newToken), 'created_at' => new Carbon];
        DB::table($this->table)->insert($payload);

        $user->notify(new ResetPasswordNotification($newToken));
        return 'passwords.sent';
    }
}
