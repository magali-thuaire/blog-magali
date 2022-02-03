<?php

namespace Core\Security;

class Security
{
	// Fonction de nettoyage d'une valeur
	public static function checkInput(string $data) : ?string {

		return htmlspecialchars(stripslashes(trim($data)));
	}

	public static function checkInputs(array $dataArray): array {

		$dataArrayChecked = [];
		foreach ($dataArray as $key => $data) {
			$dataArrayChecked[$key] = self::checkInput($data);
		}

		return $dataArrayChecked;
	}

}