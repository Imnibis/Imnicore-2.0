<?php

#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#
#									#
#			 IMNICORE 2.0			#
#									#
#			  PAR IMNIBIS			#
#									#
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#

class User implements JsonSerializable {
	private $id = NULL;
	private $name = NULL;
	private $email = NULL;
	private $token = NULL;
	private $authToken = NULL;
	private $rank = NULL;
	public function __construct() {
		if(isset($_SESSION['id']) && isset($_SESSION['name']) && isset($_SESSION['email']) && isset($_SESSION['authToken']) && isset($_SESSION['rank'])) {
			if($_SESSION['authToken'] != Imnicore::getDB()->query('SELECT * FROM ic_user_settings WHERE `uid` = ? && `name` = "auth-token"', array($_SESSION['id']))['value']) {
				unset($_SESSION['id']);
				unset($_SESSION['name']);
				unset($_SESSION['password']);
				unset($_SESSION['email']);
				unset($_SESSION['token']);
				unset($_SESSION['authToken']);
				unset($_SESSION['rank']);
			} else {
				$authToken = $this->getToken();
				$data = Imnicore::getDB()->query('SELECT * FROM ' . Imnicore::usersTable() . ' WHERE `identifier` = ?', array($_SESSION['id']));
				$this->setUserVar('id', $data['identifier']);
				$this->setUserVar('name', $data['username']);
				$this->setUserVar('password', $data['password']);
				$this->setUserVar('email', $data['email']);
				$this->setUserVar('authToken', $authToken);
				$this->setUserVar('rank', $data['rank']);
				$this->setSetting('auth-token', $authToken);
			}
		} elseif(isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
			$this->Clogin($_COOKIE['username'], $_COOKIE['password']);
		}
	}

	public function jsonSerialize() {
		return get_object_vars($this);
	}

	public function login($username, $password, bool $createCookies = false) {
    $db = Imnicore::getDB();
		$username = Imnicore::secu($username);
    $uid = $db->query("SELECT * FROM " . Imnicore::usersTable() . " WHERE `username` = ? || `email` = ?", array($username, $username))["identifier"];
    $personnalSalt = $db->query('SELECT * FROM ic_user_settings WHERE `name` = ? && `uid` = ?', array("personnalSalt", $uid));
		$hPassword = Imnicore::hash($password, $personnalSalt["value"]);
		return $this->loginStep($username, $hPassword, $createCookies);
	}

    public function Clogin($username, $hPassword) {
		$username = Imnicore::secu($username);
		$hPassword = Imnicore::secu($hPassword);
		return $this->loginStep($username, $hPassword);
	}

    private function loginStep($username, $password, $createCookies = false) {
      $db = Imnicore::getDB();
			$errored = false;
	    if(empty($username) || empty($password)) {
				$errored = true;
				$msg[] = Lang::get('form.error.empty');
			}
			$req = 'SELECT * FROM ' . Imnicore::usersTable() . ' WHERE username = ? AND password = ?';
			$isMail = false;
			if(preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/', $username)) {
				$req = 'SELECT * FROM ' . Imnicore::usersTable() . ' WHERE email = ? AND password = ?';
				$isMail = true;
			}
			$data = $db->query($req, array($username, $password));
			if(!$data) {
				$errored = true;
				$msg[] = Lang::get('login.error.invalid');
			}
			if(!$errored) {
				$authToken = $this->getToken(100);
				$this->setUserVar('id', $data['identifier']);
				$this->setUserVar('name', $data['username']);
				$this->setUserVar('password', $password);
				$this->setUserVar('email', $data['email']);
				$this->setUserVar('authToken', $authToken);
				$this->setUserVar('rank', $data['rank']);
				$this->setSetting('auth-token', $authToken);
				if($createCookies) {
					setcookie('username', $this->getName(), time() + (10 * 365 * 24 * 60 * 60));
					setcookie('password', $password, time() + (10 * 365 * 24 * 60 * 60));
				}
				return array('success' => true, 'user' => $this);
			} else {
				return array('success' => false, 'msg' => $msg);
			}
    }

	public function setUserVar($var, $value) {
		$this->$var = $value;
		$_SESSION[$var] = $value;
		return true;
	}

	public function register($username, $email, $password, $repassword) {
		$db = Imnicore::getDB();
		$errored = false;
		$msg = array();
		$username_min_chars = 3;
		$username_max_chars = 30;
		$password_min_chars = 5;
		$password_max_chars = 16;
		if(empty($username) || empty($email) || empty($password) || empty($repassword)) {
			$errored = true;
			$msg[] = Lang::get('form.error.empty');
		}
		if(!preg_match('/^[a-z\d_.-@รง!:\/;?.*%$ยฃ\(\)#{\[|\]]{' . $username_min_chars . ',' . $username_max_chars . '}$/i', $username)) {
			$errored = true;
			$msg[] = Lang::get('register.error.username.length', array('min' => $username_min_chars, 'max' => $username_max_chars));
		}
		if(!!$db->query('SELECT * FROM ' . Imnicore::usersTable() . ' WHERE username = ?', array($username))) {
			$errored = true;
			$msg[] = Lang::get('register.error.username.exists');
		}
		if(strlen($password) < $password_min_chars || strlen($password) > $password_max_chars) {
			$errored = true;
			$msg[] = Lang::get('register.error.password.length', array('min' => $password_min_chars, 'max' => $password_max_chars));
		}
		if($password != $repassword) {
			$errored = true;
			$msg[] = Lang::get('register.error.password.equal');
		}
		if(!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email)) {
			$errored = true;
			$msg[] = Lang::get('register.error.email.invalid');
		}
		if(!!$db->query('SELECT * FROM ' . Imnicore::usersTable() . ' WHERE email = ?', array($email))) {
			$errored = true;
			$msg[] = Lang::get('register.error.email.exists');
		}
		if(!$errored) {
			$username = Imnicore::secu($username);
			$email = Imnicore::secu($email);
      $personnalSalt = Imnicore::getToken(5);
			$identifier = Imnicore::getToken(Imnicore::getSetting("idLength", 5));
			while(!!$db->query('SELECT * FROM ' . Imnicore::usersTable() . ' WHERE identifier = ?', array($identifier))) {
				$identifier = Imnicore::getToken(Imnicore::getSetting("idLength", 5));
			}
			$hPassword = Imnicore::hash($password, $personnalSalt);
			$dRank = Imnicore::getSetting("defaultRank", 1);
			$temptoken = $this->getToken();
			$authToken = $this->getToken();
			$db->query('INSERT INTO ' . Imnicore::usersTable() . ' (`identifier`, `username`, `password`, `email`, `rank`, `auth_ticket`) VALUES (?, ?, ?, ?, ?, ?)', array($identifier, $username, $hPassword, $email, $dRank, $temptoken));
			$this->setUserVar('id', $identifier);
			$this->setUserVar('name', $username);
			$this->setUserVar('password', $hPassword);
			$this->setUserVar('email', $email);
			$this->setUserVar('token', $temptoken);
			$this->setUserVar('authToken', $authToken);
			$this->setUserVar('rank', $dRank);
			$this->setSetting('auth-token', $authToken);
      $this->setSetting('personnalSalt', $personnalSalt);
			return array('success' => true, 'user' => $this);
		} else {
			return array('success' => false, 'errors' => $msg);
		}
	}

	private function getToken() {
		return 'IMNICORE-' . Imnicore::getToken(91);
	}

	public function getSetting($param, $default = NULL) {
		if($this->isOnline()) {
			$db = Imnicore::getDB();
			$query = $db->query('SELECT * FROM ic_user_settings WHERE `name` = ? && `uid` = ?', array($param, $this->getID()));
			if(!$query && $default != NULL) {
				$this->setSetting($param, $default);
				$query['value'] = $default;
			}
			return ($query) ? $query['value'] : 'undefined';
		} else {
			return 'undefined';
		}
	}

	public function setSetting($param, $value) {
		if($this->isOnline()) {
			$db = Imnicore::getDB();
			$query = $db->query('SELECT * FROM ic_user_settings WHERE `name` = ? && `uid` = ?', array($param, $this->getID()));
			if($query) {
				$db->query('UPDATE ic_user_settings SET `value` = ? WHERE `name` = ? && `uid` = ?', array($value, $param, $this->getID()));
			} else {
				$db->query('INSERT INTO ic_user_settings (`id`, `uid`, `name`, `value`) VALUES (NULL, ?, ?, ?)', array($this->getID(), $param, $value));
			}
			return true;
		} else {
			return false;
		}
	}

	public function disconnect() {
		unset($_COOKIE['username']);
		unset($_COOKIE['password']);
		session_destroy();
		Imnicore::redirect(Imnicore::getPath());
	}

	public function isOnline() {
		return ($this->id != NULL);
	}

	public function getID() {
		return ($this->isOnline()) ? $this->id : 'undefined';
	}

	public function getName() {
		return ($this->isOnline()) ? $this->name : 'undefined';
	}

	public function getEmail() {
		return ($this->isOnline()) ? $this->email : 'undefined';
	}

	public function getRank() {
		return ($this->isOnline()) ? $this->rank : 'undefined';
	}

	public function get($column) {
		$data = Imnicore::getDB()->query('SELECT * FROM ' . Imnicore::usersTable() . ' WHERE identifier = ?', array($this->getID()));
		return (!$data) ? 'undefined' : $data[$column];
	}
}
