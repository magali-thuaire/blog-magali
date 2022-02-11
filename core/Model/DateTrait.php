<?php

namespace Core\Model;

use DateTime;

trait DateTrait
{
    private static array $months = [
        'january'   => 'janvier',
        'february'  => 'février',
        'march'     => 'mars',
        'april'     => 'avril',
        'may'       => 'mai',
        'june'      => 'juin',
        'july'      => 'juillet',
        'august'    => 'août',
        'september' => 'septembre',
        'october'   => 'octobre',
        'november'  => 'novembre',
        'december'  => 'décembre',
    ];

    private function dateFormatted(DateTime $dateTime, $format = 'Y-d-m h:i:s'): array|string
    {

        $date = $dateTime->format($format);
        foreach (self::$months as $month => $mois) {
            if (strpos(strtolower($date), $month)) {
                $date = str_replace($month, $mois, strtolower($date));
            }
        }

        return $date;
    }
}
