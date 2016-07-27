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
		
		self::initPath();
		self::initDynScripts();
		
		# Ajoutez ici les actions que vous voulez executer a chaque chargement de page, et pour toute les pages.
		# Add here all actions you want to be executed on all pages.
		
	}
	
	private static function initPath() {
		Imnicore::setRelativePath(preg_replace('#' . $_SERVER['DOCUMENT_ROOT'] . '#', '', preg_replace('#\\\\#', '/', realpath('.'))));
	}
	
	private static function initDynScripts() {
		DynScripts::addVar('PATH', Imnicore::getPath());
		DynScripts::addVar('LANG', Imnicore::getLang());
	}
}
