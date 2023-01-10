<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        $table = config('auth.passwords.users.table');
        $email = $user->getEmailForPasswordReset();
        //Delete all existing reset tokens from the database.
        DB::table($table)->where('email', $email)->delete();
        // We will create a new, random token for the user so that we can e-mail them
        // a safe link to the password reset form. Then we will insert a record in
        // the database so that we can verify the token within the actual reset.

        $newToken = hash_hmac('sha256', Str::random(40), config('app.key'));
        $payload = ['email' => $email, 'token' => Hash::make($newToken), 'created_at' => new Carbon];
        DB::table($table)->insert($payload);

        $user->notify(new ResetPasswordNotification($newToken));
        return 'passwords.sent';
    }

    // /**
    //  * Handle the User "updated" event.
    //  *
    //  * @param  \App\Models\User  $user
    //  * @return void
    //  */
    // public function updated(User $user)
    // {
    //     //
    // }

    // /**
    //  * Handle the User "deleted" event.
    //  *
    //  * @param  \App\Models\User  $user
    //  * @return void
    //  */
    // public function deleted(User $user)
    // {
    //     //
    // }
}
