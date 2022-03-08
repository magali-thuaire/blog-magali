<?php

namespace App\Service;

use App\Entity\UserEntity;
use App\Security\Security;

class UserMailService
{
    public static function send(
        UserEntity $user,
        $from,
        $subject
    ): bool {
        $validationLink = Security ::generateValidationLink($user);
        $message  = 'Bonjour ' . $user->getUsername() . '</br></br>';
        $message .= 'Afin d\'activer votre compte, merci de <b>cliquer sur le lien suivant</b> :' . '</br>';
        $message .= '<a style="color: #1eb1de; font-weight: bold;" href="' . $validationLink . '">
                        Je confirme mon inscription
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
