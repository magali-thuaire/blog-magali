<?php

namespace App\Model;

use App\Entity\UserEntity;
use App\Manager\SecurityManager;
use Core\Model\Mail;

class PasswordMail extends Mail
{
    private const SUBJECT = 'Blog de Magali - Confirmation de demande de mot de passe';

    public function sendEmail(UserEntity $user, $from = EMAIL_DEFAULT_FROM, $subject = self::SUBJECT): bool
    {
        $validationLink = SecurityManager::generateResetPasswordLink($user);
        $message  = 'Bonjour ' . $user->getUsername() . '</br></br>';
        $message .= 'Vous avez oublie votre mot de passe ou vous souhaitez le modifier ?'. '</br>';
        $message .= 'Pas d\'inquietude, vous pouvez le reinitialiser en cliquant sur le lien suivant :' . '</br>';
        $message .= '<a style="color: #1eb1de; font-weight: bold;" href="' . $validationLink . '">
                        Reinitialiser mon mot de passe
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
