<?php

#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#
#									#
#			 IMNICORE 2.0			#
#									#
#			  PAR IMNIBIS			#
#									#
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#

class User {
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
				unset($_SESSION['email']);
				unset($_SESSION['token']);
				unset($_SESSION['authToken']);
				unset($_SESSION['rank']);
			} else {
				$authToken = $this->getToken();
				$data = Imnicore::getDB()->query('SELECT * FROM ' . Imnicore::usersTable() . ' WHERE `id` = ?', $_SESSION['id']);
				$this->setUserVar('id', $data['id']);
				$this->setUserVar('name', $data['username']);
				$this->setUserVar('email', $data['email']);
				$this->setUserVar('authToken', $authToken);
				$this->setUserVar('rank', $data['rank']);
				$this->setSetting('auth-token', $authToken);
			}
		} elseif(isset($_COOKIE['username']) && isset($_COOKIE['password'])) {
			$this->login($_COOKIE['username'], $_COOKIE['password']);
		}
	}
	
	public function login($username, $password, bool $createCookies = false) {
		$db = Imnicore::getDB();
		$errored = false;
		$username = Imnicore::secu($username);
		$hPassword = Imnicore::hash($password);
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
		$data = query($req, array($username, $hPassword));
		if(!$data) {
			$errored = true;
			$msg[] = Lang::get('login.error.invalid');
		}
		if(!$errored) {
			$authToken = $this->getToken(100);
			$this->setUserVar('id', $data['id']);
			$this->setUserVar('name', $data['username']);
			$this->setUserVar('email', $data['email']);
			$this->setUserVar('authToken', $authToken);
			$this->setUserVar('rank', $data['rank']);
			$this->setSetting('auth-token', $authToken);
			if($createCookies) {
				setcookie('username', $this->getName(), time() + (10 * 365 * 24 * 60 * 60));
				setcookie('password', $password, time() + (10 * 365 * 24 * 60 * 60));
			}
			return array('success' => true);
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
		$username_max_chars = 15;
		$password_min_chars = 5;
		$password_max_chars = 16;
		if(empty($username) || empty($email) || empty($password) || empty($repassword)) {
			$errored = true;
			$msg[] = Lang::get('form.error.empty');
		}
		if(!preg_match('/^[a-z\d_.-@ç!:\/;?.*%$£\(\)#{\[|\]]{' . $username_min_chars . ',' . $username_max_chars . '}$/i', $username)) {
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
			$hPassword = Imnicore::hash($password);
			$temptoken = $this->getToken();
			$authToken = $this->getToken();
			$id = $db->query('INSERT INTO ' . Imnicore::usersTable() . ' (`username`, `password`, `email`, `rank`, `auth_ticket`) VALUES (?, ?, ?, ?, ?)', array($username, $hPassword, $email, $dRank, $temptoken));
			$this->setUserVar('id', $id);
			$this->setUserVar('name', $username);
			$this->setUserVar('email', $email);
			$this->setUserVar('token', $temptoken);
			$this->setUserVar('authToken', $authToken);
			$this->setUserVar('rank', $dRank);
			$this->setSetting('auth-token', $authToken);
			return array('success' => true);
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
		unset($_SESSION['id']);
		unset($_SESSION['name']);
		unset($_SESSION['email']);
		unset($_SESSION['token']);
		unset($_SESSION['authToken']);
		unset($_SESSION['rank']);
		unset($_COOKIE['username']);
		unset($_COOKIE['password']);
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
		$data = Imnicore::getDB()->query('SELECT * FROM ' . Imnicore::usersTable() . ' WHERE id = ?', array($this->getID()));
		return (!$data) ? 'undefined' : $data[$column];
	}
}