<?php

namespace Core;

class Security
{
	// Fonction de nettoyage d'une valeur
	public static function checkInput($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
}