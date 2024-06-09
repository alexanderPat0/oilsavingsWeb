<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendVerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationLink;
    public $name;
    public $uid;


    public function __construct($verificationLink, $name, $uid)
    {
        $this->verificationLink = $verificationLink;
        $this->name = $name;
        $this->uid = $uid;
    }

    public function build()
    {
        Log::info('This is some useful information.');
        return $this->view('emails.verify-email')
            ->subject('Verify Your Email Address')
            ->with([
                'verificationLink' => $this->verificationLink,
                'name' => $this->name,
                'uid' => $this->uid // Pasar UID a la vista
            ]);
    }

}
