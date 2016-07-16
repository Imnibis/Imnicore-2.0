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
	
	public static function hash($password) {
		$db = self::getDB();
		$salt1 = $db->query('SELECT * FROM ic_settings WHERE name = "salt1"');
		$salt2 = $db->query('SELECT * FROM ic_settings WHERE name = "salt2"');
		
		if(!$salt1 || !$salt2) {
			$salt1 = self::getToken(5);
			$salt2 = self::getToken(10);
			$db->query('DELETE FROM ic_settings WHERE `name` = "salt1" || `name` = "salt2"');
			$db->query('INSERT INTO ic_settings (`id`, `name`, `value`) VALUES (NULL, "salt1", ?), (NULL, "salt2", ?)', array($salt1, $salt2));
		}
		
		return sha1($salt1 . $salt2 . md5($salt1 . $password . $salt2 . $salt1) . $salt2 . $salt1 . $salt2); // I love security
	}
	
	public static function crypto_rand_secure($min, $max)
	{
		$range = $max - $min;
		if ($range < 1) return $min;
		$log = ceil(log($range, 2));
		$bytes = (int) ($log / 8) + 1;
		$bits = (int) $log + 1;
		$filter = (int) (1 << $bits) - 1;
		do {
			$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
			$rnd = $rnd & $filter;
		} while ($rnd >= $range);
		return $min + $rnd;
	}
	
	public static function usersTable($table = NULL) {
		$db = self::getDB();
		if($table == NULL) {
			return (self::installed()) ? self::getSetting('usersTable', 'users') : 'undefined';
		} else {
			if(self::installed()) {
				self::setSetting('usersTable', $table);
			}
			return self::installed();
		}
	}
	
	public static function getSetting($param, $default = NULL) {
		$db = self::getDB();
		$query = $db->query('SELECT * FROM ic_settings WHERE `name` = ?', array($param));
		if(!$query) {
			self::setSetting($param, $default);
			$query['value'] = $default;
		}
		return (self::installed()) ? $query['value'] : 'undefined';
	}

	public static function setSetting($param, $value) {
		$db = self::getDB();
		$query = $db->query('SELECT * FROM ic_settings WHERE `name` = ?', array($param));
		if($query) {
			$db->query('UPDATE TABLE ic_settings SET `value` = ? WHERE `name` = ?', array($value, $param));
		} else {
			$db->query('INSERT INTO ic_settings (`id`, `name`, `value`) VALUES (NULL, ?, ?)', array($param, $value));
		}
		return true;
	}
	
	public static function getToken($length) {
		$token = "";
		$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
		$codeAlphabet.= "0123456789";
		$max = strlen($codeAlphabet) - 1;
		for ($i=0; $i < $length; $i++) {
			$token .= $codeAlphabet[self::crypto_rand_secure(0, $max)];
		}
		return $token;
	}
	
	public static function getIcVersion() {
		return (self::installed()) ? self::getSetting('version') : '2.0';
	}
	
	public static function installed() {
		return (file_exists("settings.json"));
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