<?php

namespace App\Service;

use App\Entity\ContactEntity;

class ContactMailService
{
    public static function send(
        ContactEntity $contact,
        $to = EMAIL_DEFAULT_TO,
        $subject = EMAIL_DEFAULT_SUBJECT
    ): bool {
        $header = 'From: ' . $contact->getName() . '<' . $contact->getEmail() . '>';
        $message = $contact->getMessage();

        return mail($to, $subject, $message, $header);
    }
}
