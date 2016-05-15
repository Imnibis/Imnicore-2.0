<?php

class User {
	private $id = NULL;
	private $name = NULL;
	private $email = NULL;
	private $admin = NULL;
	private $imnicore;
	public function __construct(Imnicore $imnicore, $session) {
		$this->imnicore = $imnicore;
		if(!empty($session)) {
			$this->id = $session['id'];
			$this->name = $session['name'];
			$this->email = $session['email'];
			$data = $this->imnicore->query('SELECT admin FROM users WHERE id = ?', array($this->id));
			if($data['admin'] == 1) {
				$admin == true;
			} else {
				$admin == false;
			}
			$this->admin = $admin;
		}
	}
	public function login($username, $password) {
		$errored = false;
		$password = $this->imnicore->hash($password);
		$data = $this->imnicore->query('SELECT * FROM users WHERE username = ? AND password = ?', array($username, $password));
		if(empty($data)) {
			$errored = true;
		}
		if(!$errored) {
			if($data['admin'] == 1) {
				$admin == true;
			} else {
				$admin == false;
			}
			$this->setUserVar('id', $data['id']);
			$this->setUserVar('name', $data['username']);
			$this->setUserVar('email', $data['email']);
			$this->admin = $admin;
			return true;
		} else {
			return false;
		}
	}
	public function getId() {
		return $this->id;
	}
	public function getName() {
		return $this->name;
	}
	public function getEmail() {
		return $this->email;
	}
	public function isAdmin() {
		return $this->admin;
	}
	private function setUserVar($uvar, $uvalue) {
		$this->$uvar = $uvalue;
		$_SESSION[$uvar] = $uvalue;
	}
	public function disconnect() {
		$this->id = NULL;
		$this->name = NULL;
		$this->email = NULL;
		$this->admin = NULL;
		session_destroy();
		header('Location: ' . $this->imnicore->getPath());
		exit;
	}
	public function isConnected() {
		if($this->id == NULL) {
			return false;
		} else {
			return true;
		}
	}
	public function register($username, $password, $repassword, $email, $admin = false) {
		$errored = false;
		$msg = array();
		if($admin) {
			$dAdmin = 1;
		} else {
			$dAdmin = 0;
		}
		if(!preg_match('/^[a-z\d_]{5,20}$/i', $username)) {
			$errored = true;
			$msg[] = 'Votre pseudo doit faire entre 5 et 20 caractères et ne doit pas utiliser de caractères spéciaux.';
		}
		if(!empty($this->imnicore->query('SELECT * FROM users WHERE username = ?', array($username)))) {
			$errored = true;
			$msg[] = 'Votre pseudo est déjà pris.';
		}
		if(strlen($password) <= 4) {
			$errored = true;
			$msg[] = 'Votre mot de passe est trop court. (5 caractères minimum)';
		}
		if(strlen($password) >= 16) {
			$errored = true;
			$msg[] = 'Votre mot de passe est trop long. (15 caractères maximum)';
		}
		if($password != $repassword) {
			$errored = true;
			$msg[] = 'Vos mots de passes ne sont pas égaux.';
		}
		if(!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email)) {
			$errored = true;
			$msg[] = 'Votre email est invalide.';
		}
		if(!empty($this->imnicore->query('SELECT * FROM users WHERE email = ?', array($email)))) {
			$errored = true;
			$msg[] = 'Votre adresse email est déjà utilisée.';
		}
		if(!$errored) {
			$hPassword = $this->imnicore->hash($password);
			$id = $this->imnicore->query('INSERT INTO users (`username`, `password`, `email`, `admin`) VALUES (?, ?, ?, ?)', array($username, $hPassword, $email, $dAdmin));
			$this->setUserVar('id', $id);
			$this->setUserVar('name', $username);
			$this->setUserVar('email', $email);
			$this->setUserVar('admin', $admin);
			return array('success' => true);
		} else {
			return array('success' => false, 'errors' => $msg);
		}
	}
}