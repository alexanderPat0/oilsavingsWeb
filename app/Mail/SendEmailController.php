<?php

// namespace App\Mail;
// use Exception;

// require 'vendor/autoload.php';

// class SendEmailController {
//     private $sendgrid;

//     public function __construct($apiKey) {
//         $this->sendgrid = new \SendGrid($apiKey);
//     }

//     public function sendVerificationEmail($userEmail, $userName, $verificationLink) {
//         $email = new \SendGrid\Mail\Mail();
//         $email->setFrom("tu@correo.com", "Tu Nombre o Empresa");
//         $email->setSubject("Verifica tu correo electrónico");
//         $email->addTo($userEmail, $userName);
//         $email->addContent(
//             "text/html", 
//             "Hola $userName,<br><br>Por favor, verifica tu correo haciendo clic en este enlace: <a href='$verificationLink'>Verificar Correo</a>"
//         );

//         try {
//             $response = $this->sendgrid->send($email);
//             return $response;
//         } catch (\Exception $e) {
//             throw new Exception('Error sending email: ' . $e->getMessage());
//         }
//     }
// }
