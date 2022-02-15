<?php

namespace App\Model;

use App\Entity\UserEntity;
use App\Manager\SecurityManager;
use Core\Model\Mail;

class UserMail extends Mail
{
    private const SUBJECT = 'Blog de Magali - Activation de votre compte';

    // TODO : lien d'activation de compte
    public function sendEmail(UserEntity $user, $from = EMAIL_DEFAULT_FROM, $subject = self::SUBJECT): bool
    {
        $validationLink = SecurityManager::generateValidationLink($user);
        $message  = 'Bonjour ' . $user->getUsername() . '</br></br>';
        $message .= 'Afin d\'activer votre compte, merci de <b>cliquer sur le lien suivant</b> :' . '</br>';
        $message .= '<a style="color: #1eb1de; font-weight: bold;" href="' . $validationLink . '">
                        Je confirme mon inscription
                    </a>';

        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';
        $headers[] = 'From: ' . 'Blog de Magali' . '<' . $from . '>';

        $this
            ->setTo($user->getEmail())
            ->setSubject($subject)
            ->setMessage($message)
            ->setHeader(implode(PHP_EOL, $headers))
        ;

        return mail($this->to, $this->subject, $this->message, $this->header);
    }
}
