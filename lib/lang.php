<?php

#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#
#									#
#			 IMNICORE 2.0			#
#									#
#			  PAR IMNIBIS			#
#									#
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#

class Lang {
	private static $forceLang = NULL;
	public static function getLangVars():array {
		$file = file_get_contents('lang/' . self::getLang() . '.lang');
		return json_decode($file, true);
	}
	public static function getLang() {
		return (self::$forceLang == NULL) ? Imnicore::getLang() : self::$forceLang;
	}
	public static function forceLang(string $lang) {
		self::$forceLang = $lang;
	}
	private static function parse($langVar, $vars):string {
		if(is_array($vars)) {
			foreach($vars as $k => $v) {
				$langVar = preg_replace('#(.?)%' . $k . '%(.?)#', '${1}' . $v . '${2}', $langVar);
			}
		} else {
			$langVar = preg_replace('#(.?)%([^%]+)%(.?)#', '${1}' . $vars . '${3}', $langVar);
		}
		return $langVar;
	}
	
	public static function debug():array {
		return self::getLangVars();
	}
	
	public static function get($langVar, $vars = NULL):string {
		if(isset(self::getLangVars()[$langVar])) {
			if($vars == NULL) {
				return self::getLangVars()[$langVar];
			} else {
				return self::parse(self::getLangVars()[$langVar], $vars);
			}
		} else {
			return $langVar;
		}
	}
}