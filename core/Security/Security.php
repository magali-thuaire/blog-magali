<?php

namespace Core\Security;

use JetBrains\PhpStorm\Pure;

class Security
{
    // Fonction de nettoyage d'une valeur
    public static function checkInput(string $data): ?string
    {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    #[Pure] public static function checkInputs(array $dataArray): array
    {
        $dataArrayChecked = [];
        foreach ($dataArray as $key => $data) {
            $dataArrayChecked[$key] = static::checkInput($data);
        }

        return $dataArrayChecked;
    }

    public static function generateToken(): string
    {
        return uniqid(rand(), true);
    }
}
