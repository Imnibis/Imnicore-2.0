<?php

#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#
#									#
#			 IMNICORE 2.0			#
#									#
#			  PAR IMNIBIS			#
#									#
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#

class Lang {
	private static function getLangVars() {
		$file = file_get_contents('lang/' . Imnicore::getLang() . '.lang');
		return json_decode($file, true);
	}
	private static function parse($langVar, $vars) {
		if(is_array($vars)) {
			preg_match('%(.+)%', $langVar, $matches);
			foreach($matches as $k => $v) {
				foreach($vars as $k2 => $v2) {
					if($v == $k2) {
						preg_replace('%' . $v . '%', $v2, $langVar);
						return $langVar;
					}
				}
			}
		} else {
			preg_replace('%(.+)%', $vars, $langVar);
			return $langVar;
		}
	}
	
	public static function get($langVar, $vars = NULL) {
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