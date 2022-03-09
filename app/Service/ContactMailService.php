<?php

namespace App\Service;

use App\Entity\ContactEntity;

class ContactMailService
{
    public static function send(
        ContactEntity $contact,
        $to,
        $subject
    ): bool {
        $header = 'From: ' . $contact->getName() . '<' . $contact->getEmail() . '>';
        $message = $contact->getMessage();

        return mail($to, $subject, $message, $header);
    }
}
