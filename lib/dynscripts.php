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
	public static function addVar(string $var, string $replacement) {
		self::$vars[] = '#{{' . $var . '}}#';
		self::$replacements[] = $replacement;
		return true;
	}
	public static function getVars() {
		return self::$vars;
	}
	public static function getReplacements() {
		return self::$replacements;
	}
	public static function processLangVars(&$script) {
		foreach(Lang::getLangVars() as $k => $v) {
			$script = preg_replace('#{{LANG:' . $k . '}}#', $v, $script);
		}
	}
}