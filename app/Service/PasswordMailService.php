<?php

namespace App\Service;

use App\Entity\UserEntity;
use App\Security\Security;

class PasswordMailService
{
    private const SUBJECT = 'Blog de Magali - Confirmation de demande de mot de passe';

    public static function send(
        UserEntity $user,
        $from = EMAIL_DEFAULT_FROM,
        $subject = self::SUBJECT
    ): bool {
        $validationLink = Security ::generateResetPasswordLink($user);
        $message  = 'Bonjour ' . $user->getUsername() . '</br></br>';
        $message .= 'Vous avez oublie votre mot de passe ou vous souhaitez le modifier ?' . '</br>';
        $message .= 'Pas d\'inquietude, vous pouvez le reinitialiser en cliquant sur le lien suivant :' . '</br>';
        $message .= '<a style="color: #1eb1de; font-weight: bold;" href="' . $validationLink . '">
                        Reinitialiser mon mot de passe
                    </a>';

        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=iso-8859-1';
        $headers[] = 'From: ' . 'Blog de Magali' . '<' . $from . '>';

        $to = $user->getEmail();
        $header = implode(PHP_EOL, $headers);

        return mail($to, $subject, $message, $header);
    }
}
