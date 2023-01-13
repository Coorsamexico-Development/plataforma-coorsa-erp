<?php

namespace App\Notifications;

use App\Mail\WelcomeEmployde;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;



    /**
     * The callback that should be used to build the mail message.
     *
     * @var (\Closure(mixed, string): \Illuminate\Notifications\Messages\MailMessage)|null
     */
    public static $toMailCallback;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $nombre = $notifiable->name . ' ' . $notifiable->apellido_paterno;
        $email = $notifiable->getEmailForPasswordReset();
        return $this->buildMailMessage($this->resetUrl($notifiable), $nombre, $email);
    }

    /**
     * Get the reset password notification mail message for the given URL.
     *
     * @param  string  $url
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    protected function buildMailMessage($url, $nombre, $email)
    {

        $users = User::select('users.email')
        ->where('users.activo','=',)
        ->get();

        for ($i=0; $i < count($users); $i++) 
        { 
            $correo = $users[$i]->email;
            return (new WelcomeEmployde('Bienvenido a Coorsa.', $correo, $url));
        }

  
        // return (new MailMessage)
        //     ->(['imageWelcome' => true])
        //     ->subject('Bienvenido a Coorsa.')
        //     // ->greeting('!HOLA! ' . $nombre)
        //     // ->line('Está recibiendo este correo electrónico porque acabas ser dado de alta en nuestro portal.')
        //     // ->line('Es necesario que ingreses al portal para que establezcas tu contraseña.')
        //     ->action('Establecer contraseña', $url)
        //     ->salutation('!Saludos!');
    }

    /**
     * Get the reset URL for the given notifiable.
     *
     * @param  mixed  $notifiable
     * @return string
     */
    protected function resetUrl($notifiable)
    {
        return url(route('password.reset.first', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));
    }
}
