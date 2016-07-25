<?php 

#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#
#									#
#			 IMNICORE 2.0			#
#									#
#			  PAR IMNIBIS			#
#									#
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#

class Globals {
	public static function init() {
		
		# Ajoutez ici les actions que vous voulez executer a chaque chargement de page, et pour toute les pages.
		# Add here all actions you want to be executed on all pages.
		
		self::initPath();
		self::initDynScripts();
	}
	
	private static function initPath() {
		$path = explode('\\', dirname(dirname(__FILE__)));
		$path = explode('/', end($path));
		$path = end($path);
		Imnicore::setRelativePath($path);
	}
	
	private static function initDynScripts() {
		DynScripts::addVar('PATH', Imnicore::getPath());
		DynScripts::addVar('LANG', Imnicore::getLang());
	}
}
