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
	public function __construct() {
		// TODO: Do something when Imnicore inits.
	}
	
	public static function init() {
		$json = file_get_contents('settings.json');
		$config = json_decode($json, true);
		$db = new Database($config['database']['host'], $config['database']['user'], $config['database']['password'], $config['database']['name']);
		return $db;
	}
}