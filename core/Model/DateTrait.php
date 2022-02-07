<?php

namespace Core\Model;

use DateTime;

trait DateTrait
{
	private static $_months = [
		'january' 	=> 'janvier',
		'february' 	=> 'février',
		'march' 	=> 'mars',
		'april' 	=> 'avril',
		'may' 		=> 'mai',
		'june' 		=> 'juin',
		'july' 		=> 'juillet',
		'august' 	=> 'août',
		'september' => 'septembre',
		'october' 	=> 'octobre',
		'november' 	=> 'novembre',
		'december' 	=> 'décembre',
	];

	private function dateFormatted(DateTime $dateTime, $format = 'Y-d-m h:i:s') {

		$date = $dateTime->format($format);
		foreach (self::$_months as $month => $mois) {
			if(strpos(strtolower($date), $month)) {
				$date = str_replace($month, $mois, strtolower($date));
			}
		}

		return $date;
	}
}