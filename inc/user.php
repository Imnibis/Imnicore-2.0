<?php

#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#
#									#
#			 IMNICORE 2.0			#
#									#
#			  PAR IMNIBIS			#
#									#
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#

class User {
	public function __construct() {
		
	}
	
	public function login($username, $password) {
		$errored = false;
		$password = $this->imnicore->hash($password);
		if(empty($username) || empty($password)) {
			$errored = true;
			$msg[] = "Tous les champs doivent être remplis.";
		}
		$req = 'SELECT * FROM users WHERE username = ? AND password = ?';
		$isMail = false;
		if(preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/', $username)) {
			$req = 'SELECT * FROM ' . $this->imnicore->getUsersTable() . ' WHERE email = ? AND password = ?';
			$isMail = true;
		}
		$data = $this->imnicore->query($req, array($username, $password));
		if(!$data) {
			$errored = true;
			$msg[] = "Pseudo ou mot de passe incorrect.";
		}
		if(!$errored) {
			$authToken = $this->imnicore->getToken(100);
			$req = $this->imnicore->query('SELECT * FROM ic_user_settings WHERE uid = ? && name = "auth-token"', array($data['id']));
			if(!$req) {
				$this->imnicore->query('INSERT INTO ic_user_settings (`id`, `uid`, `name`, `value`) VALUES (NULL, ?, "auth-token", ?)', array($data['id'], $authToken));
			} else {
				$this->imnicore->query('UPDATE ic_user_settings SET value = ? WHERE id = ?', array($authToken, $req['id']));
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
	
	public function register($username, $email, $password, $repassword) {
		
	}
	
	public function disconnect() {
		
	}
	
	public function isOnline() {
		
	}
	
	public function getName() {
		
	}
	
	public function getEmail() {
		
	}
}