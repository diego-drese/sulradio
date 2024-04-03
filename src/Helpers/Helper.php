<?php

namespace Oka6\SulRadio\Helpers;


use Illuminate\Support\Facades\Config;
use Oka6\Admin\Library\MongoUtils;

class Helper {
	public static function moneyToBco($string) {
		$string = str_replace('R$ ', '', $string);
		return (double)str_replace(',', '.', str_replace('.', '', $string));
	}
	public static function formatInteger($number) {
		return number_format($number, 0, ',', '.');
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
	public static function stripAccents($stripAccents){
		$char_map = array(
			"ъ" => "-", "ь" => "-", "Ъ" => "-", "Ь" => "-",
			"А" => "A", "Ă" => "A", "Ǎ" => "A", "Ą" => "A", "À" => "A", "Ã" => "A", "Á" => "A", "Æ" => "A", "Â" => "A", "Å" => "A", "Ǻ" => "A", "Ā" => "A", "א" => "A",
			"Б" => "B", "ב" => "B", "Þ" => "B",
			"Ĉ" => "C", "Ć" => "C", "Ç" => "C", "Ц" => "C", "צ" => "C", "Ċ" => "C", "Č" => "C", "©" => "C", "ץ" => "C",
			"Д" => "D", "Ď" => "D", "Đ" => "D", "ד" => "D", "Ð" => "D",
			"È" => "E", "Ę" => "E", "É" => "E", "Ë" => "E", "Ê" => "E", "Е" => "E", "Ē" => "E", "Ė" => "E", "Ě" => "E", "Ĕ" => "E", "Є" => "E", "Ə" => "E", "ע" => "E",
			"Ф" => "F", "Ƒ" => "F",
			"Ğ" => "G", "Ġ" => "G", "Ģ" => "G", "Ĝ" => "G", "Г" => "G", "ג" => "G", "Ґ" => "G",
			"ח" => "H", "Ħ" => "H", "Х" => "H", "Ĥ" => "H", "ה" => "H",
			"I" => "I", "Ï" => "I", "Î" => "I", "Í" => "I", "Ì" => "I", "Į" => "I", "Ĭ" => "I", "И" => "I", "Ĩ" => "I", "Ǐ" => "I", "י" => "I", "Ї" => "I", "Ī" => "I", "І" => "I",
			"Й" => "J", "Ĵ" => "J",
			"ĸ" => "K", "כ" => "K", "Ķ" => "K", "К" => "K", "ך" => "K",
			"Ł" => "L", "Ŀ" => "L", "Л" => "L", "Ļ" => "L", "Ĺ" => "L", "Ľ" => "L", "ל" => "L",
			"מ" => "M", "М" => "M", "ם" => "M",
			"Ñ" => "N", "Ń" => "N", "Н" => "N", "Ņ" => "N", "ן" => "N", "Ŋ" => "N", "נ" => "N", "ŉ" => "N", "Ň" => "N",
			"Ø" => "O", "Ó" => "O", "Ò" => "O", "Ô" => "O", "Õ" => "O", "О" => "O", "Ő" => "O", "Ŏ" => "O", "Ō" => "O", "Ǿ" => "O", "Ǒ" => "O", "Ơ" => "O",
			"פ" => "P", "ף" => "P", "П" => "P",
			"ק" => "Q",
			"Ŕ" => "R", "Ř" => "R", "Ŗ" => "R", "ר" => "R", "Р" => "R", "®" => "R",
			"Ş" => "S", "Ś" => "S", "Ș" => "S", "Š" => "S", "С" => "S", "Ŝ" => "S", "ס" => "S",
			"Т" => "T", "Ț" => "T", "ט" => "T", "Ŧ" => "T", "ת" => "T", "Ť" => "T", "Ţ" => "T",
			"Ù" => "U", "Û" => "U", "Ú" => "U", "Ū" => "U", "У" => "U", "Ũ" => "U", "Ư" => "U", "Ǔ" => "U", "Ų" => "U", "Ŭ" => "U", "Ů" => "U", "Ű" => "U", "Ǖ" => "U", "Ǜ" => "U", "Ǚ" => "U", "Ǘ" => "U",
			"В" => "V", "ו" => "V",
			"Ý" => "Y", "Ы" => "Y", "Ŷ" => "Y", "Ÿ" => "Y",
			"Ź" => "Z", "Ž" => "Z", "Ż" => "Z", "З" => "Z", "ז" => "Z",
			"а" => "a", "ă" => "a", "ǎ" => "a", "ą" => "a", "à" => "a", "ã" => "a", "á" => "a", "æ" => "a", "â" => "a", "å" => "a", "ǻ" => "a", "ā" => "a", "א" => "a",
			"б" => "b", "þ" => "b",
			"ĉ" => "c", "ć" => "c", "ç" => "c", "ц" => "c", "ċ" => "c", "č" => "c",
			"Ч" => "ch", "ч" => "ch",
			"д" => "d", "ď" => "d", "đ" => "d", "ð" => "d",
			"è" => "e", "ę" => "e", "é" => "e", "ë" => "e", "ê" => "e", "е" => "e", "ē" => "e", "ė" => "e", "ě" => "e", "ĕ" => "e", "є" => "e", "ə" => "e", "ע" => "e",
			"ф" => "f", "ƒ" => "f",
			"ğ" => "g", "ġ" => "g", "ģ" => "g", "ĝ" => "g", "г" => "g", "ґ" => "g",
			"ħ" => "h", "х" => "h", "ĥ" => "h",
			"i" => "i", "ï" => "i", "î" => "i", "í" => "i", "ì" => "i", "į" => "i", "ĭ" => "i", "ı" => "i", "и" => "i", "ĩ" => "i", "ǐ" => "i", "י" => "i", "ї" => "i", "ī" => "i", "і" => "i",
			"й" => "j", "ĵ" => "j",
			"ķ" => "k", "к" => "k",
			"ł" => "l", "ŀ" => "l", "л" => "l", "ļ" => "l", "ĺ" => "l", "ľ" => "l",
			"м" => "m",
			"ñ" => "n", "ń" => "n", "н" => "n", "ņ" => "n", "ŋ" => "n", "ň" => "n",
			"ø" => "o", "ó" => "o", "ò" => "o", "ô" => "o", "õ" => "o", "о" => "o", "ő" => "o", "ŏ" => "o", "ō" => "o", "ǿ" => "o", "ǒ" => "o", "ơ" => "o",
			"п" => "p",
			"ŕ" => "r", "ř" => "r", "ŗ" => "r", "р" => "r",
			"ş" => "s", "ś" => "s", "ș" => "s", "š" => "s", "с" => "s", "ŝ" => "s",
			"т" => "t", "ț" => "t", "ŧ" => "t", "ť" => "t", "ţ" => "t",
			"ù" => "u", "û" => "u", "ú" => "u", "ū" => "u", "у" => "u", "ũ" => "u", "ư" => "u", "ǔ" => "u", "ų" => "u", "ŭ" => "u", "ů" => "u", "ű" => "u", "ǖ" => "u", "ǜ" => "u", "ǚ" => "u", "ǘ" => "u",
			"в" => "v",
			"ý" => "y", "ы" => "y", "ŷ" => "y", "ÿ" => "y",
			"ź" => "z", "ž" => "z", "ż" => "z", "з" => "z",  "ſ" => "z",
			"™" => "tm",
			"@" => "at",
			"Ä" => "ae", "Ǽ" => "ae", "ä" => "ae", "ǽ" => "ae",
			"ĳ" => "ij", "Ĳ" => "ij",
			"я" => "ja", "Я" => "ja",
			"Э" => "je", "э" => "je",
			"ё" => "jo", "Ё" => "jo",
			"ю" => "ju", "Ю" => "ju",
			"œ" => "oe", "Œ" => "oe", "ö" => "oe", "Ö" => "oe",
			"щ" => "sch", "Щ" => "sch",
			"ш" => "sh", "Ш" => "sh",
			"ß" => "ss",
			"Ü" => "ue",
			"Ж" => "zh", "ж" => "zh",
		);
		return strtr($stripAccents, $char_map);
	}
	
	public static function slugify($text, $capitalize = false) {
		// replace non letter or digits by -
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);
		// transliterate
		$text = self::stripAccents($text);
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
			$times = explode(':', $date);
			if(count($times)==3){
				$format .= ' H:i:s';
			}elseif(count($times)==2){
				$format .= ' H:i';
			}elseif(count($times)==1){
				$format .= ' H';
			}

		}
		$dateBr = \DateTime::createFromFormat($format, $date);
		return $dateBr->format('Y-m-d H:i:s');
	}
	public static function getEmails($tries=null){
		$env = Config::get('app.env');
		if($env=='local'){
			$emailSent = collect(Config::get('multimail.emails'))->reject(function ($array){
				return $array['provider']!='mailtrap';
			});
		}else{
			$emailSent = collect(Config::get('multimail.emails'))->reject(function ($array){
				return $array['provider']=='mailtrap';
			});

			if($tries && is_array($tries) && count($tries)){
				$emailSent = $emailSent->reject(function ($array) use($tries){
					return in_array($array['email'], $tries);
				});
			}
		}
		return $emailSent;
	}

	public static function getEmailCount(){
		return self::getEmails()->count();
	}

	public static function sendEmailRandom($tries=null){
        $emails = self::getEmails($tries);
        if(count($emails)>1){
            return $emails->random();
        }
        return $emails;
	}
	
}
