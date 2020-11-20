<?php

namespace Oka6\SulRadio\Helpers;


use Oka6\Admin\Library\MongoUtils;

class Helper {
	public static function moneyToBco($string) {
		$string = str_replace('R$ ', '', $string);
		return (double)str_replace(',', '.', str_replace('.', '', $string));
	}
	
	public static function bcoToMoney($text) {
		return number_format($text, 2, ",", ".");
	}
	
	public static function convertDateBrToMongo($date = null, $defaultNow = false) {
		if (!$date && !$defaultNow) {
			return null;
		}
		
		if (!$date && $defaultNow == true) {
			return MongoUtils::convertDatePhpToMongo(date('Y-m-d H:i:s'));
		}
		$format = 'd/m/Y';
		if ($date && strpos($date, ':')) {
			if (strpos($date, 'H')) {
				$format .= ' H';
				if (strpos($date, 'i')) {
					$format .= ':i';
				}
				if (strpos($date, 's')) {
					$format .= ':s';
				}
			}
		}
		$dateBr = \DateTime::createFromFormat($format, $date);
		return MongoUtils::convertDatePhpToMongo($dateBr->format('Y-m-d H:i:s'));
	}
	
	public static function slugify($text, $capitalize = false) {
		// replace non letter or digits by -
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);
		// transliterate
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);
		// trim
		$text = trim($text, '-');
		// remove duplicate -
		$text = preg_replace('~-+~', '-', $text);
		// lowercase
		if ($capitalize) {
			$text = strtoupper($text);
		} else {
			$text = strtolower($text);
		}
		
		
		if (empty($text)) {
			return 'n-a';
		}
		
		return $text;
	}
	
	public static function convertDateBrToMysql($date = null, $defaultNow = false) {
		if (!$date && !$defaultNow) {
			return null;
		}
		
		if (!$date && $defaultNow == true) {
			return date('Y-m-d H:i:s');
		}
		$format = 'd/m/Y';
		if ($date && strpos($date, ':')) {
			if (strpos($date, 'H')) {
				$format .= ' H';
				if (strpos($date, 'i')) {
					$format .= ':i';
				}
				if (strpos($date, 's')) {
					$format .= ':s';
				}
			}
		}
		$dateBr = \DateTime::createFromFormat($format, $date);
		return $dateBr->format('Y-m-d H:i:s');
	}
	
}
