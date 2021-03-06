<?php

#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#
#									#
#			 IMNICORE 2.0			#
#									#
#			  PAR IMNIBIS			#
#									#
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#

session_start();
class Imnicore {
	private static $db = NULL;
	private static $page = NULL;
	private static $relativePath = "";
	private static $isAuthorized = array();
	
	private static function getConfig() {
		$json = file_get_contents('settings.json');
		return json_decode($json, true);
	}
	
	public static function getDB() {
		if(self::$db == NULL) {
			$config = self::getConfig();
			return self::$db = new Database($config['database']['host'], $config['database']['user'], $config['database']['password'], $config['database']['name']);
		} else {
			return self::$db;
		}
	}
	
	public static function isAuthorized(string $case, bool $bool = NULL) {
		switch($bool) {
			case NULL:
				return (is_array(self::$isAuthorized)) ? (isset(self::$isAuthorized[$case]) ? self::$isAuthorized[$case] : false) : self::$isAuthorized;
			break;
			default:
				if($case = '*') {
					self::$isAuthorized = $bool;
				} else {
					self::$isAuthorized[$case] = $bool;
				}
				return true;
			break;
		}
	}
	
	public static function setRelativePath($path) {
		self::$relativePath = $path;
	}
	
	public static function getRelativePath() {
		return self::$relativePath;
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
	
	public static function getLangs() {
		$langs = array();
		foreach(glob('lang/*.lang') as $lang) {
			$langs[] = preg_replace('#lang/(.+).lang#', '$1', $lang);
		}
		return $langs;
	}
	public static function getDefaultPath() {
		return 'http://' . $_SERVER['HTTP_HOST'] . self::getRelativePath();
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
	
	public static function crypto_rand_secure($min, $max):string
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
			self::setSetting('usersTable', $table);
			return 'done';
		}
	}
	
	public static function getSetting($param, $default = NULL) {
		$db = self::getDB();
		$query = $db->query('SELECT * FROM ic_settings WHERE `name` = ?', array($param));
		if(!$query && $default != NULL) {
			self::setSetting($param, $default);
			$query['value'] = $default;
		}
		return (self::installed() && $query) ? $query['value'] : 'undefined';
	}

	public static function setSetting($param, $value) {
		$db = self::getDB();
		$query = $db->query('SELECT * FROM ic_settings WHERE `name` = ?', array($param));
		if($query) {
			$db->query('UPDATE ic_settings SET `value` = ? WHERE `name` = ?', array($value, $param));
		} else {
			$db->query('INSERT INTO ic_settings (`id`, `name`, `value`) VALUES (NULL, ?, ?)', array($param, $value));
		}
		return true;
	}
	
	public static function secu($str) {
		return htmlspecialchars($str);
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
		if(!file_exists('settings.json')) {
			return false;
		}
		$installed = self::getDB()->query('SELECT * FROM ic_settings WHERE `name` = "installed"');
		if(!$installed) {
			return false;
		} elseif($installed['value'] == "0") {
			return false;
		} else {
			return true;
		}
	}
	
	public static function redirect($url, $javascript = false) {
		if(!$javascript) {
			header('Location: ' . $url);
			exit;
		} else {
			echo '<script>document.location.href = "' . $url . '"</script>';
		}
	}
	
	public static function getPageID($override = false) {
		return (self::$page == NULL || $override) ? (isset($_GET['page']) ? $_GET['page'] : 'index') : self::$page;
	}
	
	public static function is404($bool = NULL) {
		switch($bool) {
			case true:
				self::setPage('404');
				return true;
			break;
			case false:
				self::setPage(self::getPageID(true));
				return true;
			break;
			case NULL:
				return (self::getPageID() == '404');
			break;
			default:
				return false;
			break;
		}
	}
	
	public static function setPage($page) {
		self::$page = $page;
		return true;
	}
	
    public static function getName() {
        return (self::installed()) ? self::getSetting('name') : 'Imnicore';
    }
    
	public static function getRequestedPage() {
		return (isset($_GET['page'])) ? $_GET['page'] : 'index';
	}
	
	public static function getPath() {
		return (self::installed()) ? self::getSetting('path') : self::getRelativePath();
	}
	
	public static function getLang() {
		return (self::installed()) ? self::getSetting('lang') : 'en';
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