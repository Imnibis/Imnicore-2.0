<?php

#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#
#									#
#			 IMNICORE 2.0			#
#									#
#			  PAR IMNIBIS			#
#									#
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#

session_start();
require('model/database.php');
class Imnicore {

	private static function getConfig() {
		$json = file_get_contents('settings.json');
		return json_decode($json, true);
	}
	
	public static function getDB() {
		$config = self::getConfig();
		return new Database($config['database']['host'], $config['database']['user'], $config['database']['password'], $config['database']['name']);
	}
	
	public static function rmdir($dir) { 
   		if (is_dir($dir)) { 
     		$objects = scandir($dir); 
     		foreach ($objects as $object) { 
      			if ($object != "." && $object != "..") { 
         			if (is_dir($dir."/".$object)) {
           				self::rmdir($dir."/".$object);
         			} else {
           				unlink($dir."/".$object); 
         			}
      			}
			}
		}
    rmdir($dir); 
	}
	
	public static function getIcVersion() {
		return (self::installed()) ? self::getSetting('version') : '2.0';
	}
	
	public static function installed() {
		return (file_exists("settings.json"));
	}
	
	public static function getSetting($param) {
		return (self::installed()) ? self::getDB()->query('SELECT * FROM ic_settings WHERE name=?', array($param))['value'] : 'undefined';
	}
	
	public static function redirect($url, $javascript = false) {
		if(!$javascript) {
			header('Location: ' . $url);
			exit;
		} else {
			echo '<script>document.location.href = "' . $url . '"</script>';
		}
	}
	
	public static function getRequestedPage() {
		return (isset($_GET['page'])) ? $_GET['page'] : 'index';
	}
	
	public static function getPath() {
		return self::getSetting('path');
	}
	
	public static function getLang() {
		
		return (self::installed()) ? self::getSetting('lang') : 'fr';
	}
	
	public static function getTheme() {
		return (self::installed()) ? self::getSetting('theme') : 'default';
	}
	
	public static function init() {
		if(!self::installed() && self::getRequestedPage() != 'imnicore/install') {
			self::redirect('imnicore/install');
		}
		return (self::installed()) ? array('database' => self::getDB()) : array('database' => 'undefined');
	}
}