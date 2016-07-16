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
		
	}
	
	public function login($username, $password) {
		$db = Imnicore::getDB();
		$errored = false;
		$password = Imnicore::hash($password);
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
		$data = query($req, array($username, $password));
		if(!$data) {
			$errored = true;
			$msg[] = Lang::get('login.error.invalid');
		}
		if(!$errored) {
			$authToken = Imnicore::getToken(100);
			$req = $db->query('SELECT * FROM ic_user_settings WHERE uid = ? && name = "auth-token"', array($data['id']));
			if(!$req) {
				$db->query('INSERT INTO ic_user_settings (`id`, `uid`, `name`, `value`) VALUES (NULL, ?, "auth-token", ?)', array($data['id'], $authToken));
			} else {
				$db->query('UPDATE ic_user_settings SET value = ? WHERE id = ?', array($authToken, $req['id']));
			}
			$this->setUserVar('id', $data['id']);
			$this->setUserVar('name', $data['username']);
			$this->setUserVar('email', $data['email']);
			$this->setUserVar('authToken', $authToken);
			$this->setUserVar('rank', $data['rank']);
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
			$temptoken = 'IMNICORE-';
			$temptoken .= Imnicore::getToken(91);
			$authToken = 'IMNICORE-';
			$authToken .= Imnicore::getToken(91);
			$id = $db->query('INSERT INTO ' . Imnicore::usersTable() . ' (`username`, `password`, `email`, `rank`, `auth_ticket`) VALUES (?, ?, ?, ?, ?)', array($username, $hPassword, $email, $dRank, $temptoken));
			$db->query('INSERT INTO ic_user_settings (`id`, `uid`, `name`, `value`) VALUES (NULL, ?, "auth-token", ?)', array($id, $authToken));
			$this->setUserVar('id', $id);
			$this->setUserVar('name', $username);
			$this->setUserVar('email', $email);
			$this->setUserVar('token', $temptoken);
			$this->setUserVar('authToken', $authToken);
			$this->setUserVar('rank', $dRank);
			return array('success' => true);
		} else {
			return array('success' => false, 'errors' => $msg);
		}
	}
	
	public function disconnect() {
		unset($_SESSION['id']);
		unset($_SESSION['name']);
		unset($_SESSION['email']);
		unset($_SESSION['token']);
		unset($_SESSION['authToken']);
		unset($_SESSION['rank']);
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