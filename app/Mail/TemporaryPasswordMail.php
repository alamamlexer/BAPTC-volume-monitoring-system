<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TemporaryPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $temporaryPassword;

    /**
     * Create a new message instance.
     *
     * @param User $user
     * @param string $temporaryPassword
     */
    public function __construct(User $user, $temporaryPassword)
    {
        $this->user = $user;
        $this->temporaryPassword = $temporaryPassword;
    }

    /**
     * Build the message.
     *
     * @return \Illuminate\Contracts\Mail\Mailable
     */
    public function build()
    {
        // Generate the login URL
        $loginUrl = route('login'); // The URL for login page

        return $this->subject('Your Temporary Password')
        ->view('emails.password.reset-account')  // Points to the email view
                    ->with([
                        'username' => $this->user->name,         // User's name (Username)
                        'temporaryPassword' => $this->temporaryPassword,  // Temporary password
                        'loginUrl' => $loginUrl,                  // Login URL
                    ]);
    }
}