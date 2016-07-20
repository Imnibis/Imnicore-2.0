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
			foreach($vars as $k => $v) {
				$langVar = preg_replace('#(.)%' . $k . '%(.)#', '${1}' . $v . '${2}', $langVar);
			}
		} else {
			$langVar = preg_replace('#(.)%([^%]+)%(.)#', '${1}' . $vars . '${3}', $langVar);
		}
		return $langVar;
	}
	
	public static function debug() {
		return self::getLangVars();
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