<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendVerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationLink;  // Asegúrate de que esta propiedad se inicialice correctamente.

    public function __construct($verificationLink)
    {
        $this->verificationLink = $verificationLink;  // Aquí recibes y asignas la URL de verificación.
    }

    public function build()
    {
        return $this->view('emails.verify-email')
            ->subject('Verify Your Email Address')
            ->with(['verificationLink' => $this->verificationLink]);
    }

}
