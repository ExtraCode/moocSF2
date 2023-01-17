<?php

namespace App\Service;

use App\Repository\UserRepository;

class MailService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function sendMail(String $sujet, String $message): string
    {
        //code pour envoyer un email
        //$mail->setSubject($sujet);
        //$mail->setMessage($message);
        //$mail->send();

        dd($sujet);
    }
}