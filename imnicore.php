<?php

#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#
#									#
#			   IMNICORE				#
#									#
#			  PAR IMNIBIS			#
#									#
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#

session_start();
require('model/database.php');
class Imnicore {
	private static function getDB() {
		$config = self::getConfig();
		return new Database($config['database']['host'], $config['database']['user'], $config['database']['password'], $config['database']['name']);
	}
	
	private static function getConfig() {
		$json = file_get_contents('settings.json');
		return json_decode($json, true);
	}
	
	public static function getSetting($param) {
		return self::getDB()->query('SELECT * FROM ic_settings WHERE name=?', array($param))['value'];
	}
	
	public static function getTheme() {
		return self::getSetting('theme');
	}
	
	public static function init() {
		return array('database' => self::getDB());
	}
}