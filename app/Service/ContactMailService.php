<?php

namespace App\Service;

use App\Entity\ContactEntity;

class ContactMailService
{
    private const TO = 'magali.thuaire@gmail.com';
    private const SUBJECT = 'Message de votre blog';

    public static function send(
        ContactEntity $contact,
        $to = self::TO,
        $subject = self::SUBJECT
    ): bool {
        $header = 'From: ' . $contact->getName() . '<' . $contact->getEmail() . '>';
        $message = $contact->getMessage();

        return mail($to, $subject, $message, $header);
    }
}
