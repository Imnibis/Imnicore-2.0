<?php

#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#
#									#
#			 IMNICORE 2.0			#
#									#
#			  PAR IMNIBIS			#
#									#
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#

class DynScripts {
	private static $vars = array();
	private static $replacements = array();
	public static function addVar(string $var, string $replacement):bool {
		self::$vars[] = '#{{' . $var . '}}#';
		self::$replacements[] = $replacement;
		return true;
	}
	public static function getVars():array {
		return self::$vars;
	}
	public static function getReplacements():array {
		return self::$replacements;
	}
}