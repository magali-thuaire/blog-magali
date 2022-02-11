<?php

namespace App\Model;

use App\Entity\UserEntity;
use Core\Model\Mail;

class UserMail extends Mail
{

	// TODO : lien d'activation de compte
	public function sendEmail(UserEntity $user, $from = EMAIL_DEFAULT_FROM, $subject = 'Blog de Magali - Activation de votre compte'): bool
	{
		$lien = $user->getValidationToken();
		$message  = 'Bonjour ' . $user->getUsername() . '</br></br>';
		$message .= "Afin d'activer votre compte, merci de <b>cliquer sur le lien suivant</b> :" . '</br>';
		$message .= "<a href=$lien><b><font color='#1eb1de'><u>Je confirme mon inscription</u></font></b></a>";

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