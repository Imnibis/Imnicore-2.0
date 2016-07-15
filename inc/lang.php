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
	public static function get($var) {
		return (isset(self::getLangVars()[$var])) ? self::getLangVars()[$var] : $var;
	}
}