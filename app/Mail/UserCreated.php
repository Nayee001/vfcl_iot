<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class UserCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $passwords;

    /**
     * Create a new message instance.
     *
     * @param  User  $user
     * @return void
     */
    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->passwords = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        $loginUrl = url('/login');
        return $this->subject('Welcome to Our Platform!')
            ->markdown('emails.created', [
                'userId' => $this->user->user_id,
                'userName' => $this->user->fname,
                'userEmail' => $this->user->email,
                'userPassword' => $this->passwords,
                'loginUrl' => $loginUrl
            ]);
    }
}
