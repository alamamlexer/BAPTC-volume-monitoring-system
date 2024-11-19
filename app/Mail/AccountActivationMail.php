<?php

namespace App\Mail;

use App\Models\Staff;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $staff;
    protected $token;
    protected $temporaryPassword;

    /**
     * Create a new message instance.
     *
     * @param Staff $staff
     * @param string $token
     * @param string $temporaryPassword
     */
    public function __construct(Staff $staff, $token, $temporaryPassword)
    {
        $this->staff = $staff;
        $this->token = $token;
        $this->temporaryPassword = $temporaryPassword;
    }

    /**
     * Build the message.
     *
     * @return \Illuminate\Contracts\Mail\Mailable
     */
    public function build()
    {
        // Generate the activation URL with the token
        $activationUrl = route('account.activate', ['token' => $this->token]);

        return $this->subject('Account Activation')
                    ->view('emails.account.activation')  // Points to the email view
                    ->with([
                        'staffName' => $this->staff->staff_name,  // Staff name (Username)
                        'activationUrl' => $activationUrl,      // Activation URL
                        'username' => $this->staff->staff_name,  // Username
                        'temporaryPassword' => $this->temporaryPassword,  // Temporary password
                    ]);
    }
}