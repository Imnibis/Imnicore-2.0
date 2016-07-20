<?php

class User {
	private $id = NULL;
	private $name = NULL;
	private $email = NULL;
	private $rank = NULL;
	private $token = NULL;
	private $usersTable = "users";
	private $imnicore;
	public function __construct(Imnicore $imnicore, $session) {
		$this->imnicore = $imnicore;
		if($this->imnicore->getSetting('habbo') == 1 && $this->imnicore->getSetting('emulator') == "comet") {
			$this->usersTable = "players";
		}
		if(!empty($session)) {
			$this->id = $session['id'];
			$this->name = $session['name'];
			$this->email = $session['email'];
			$data = $this->imnicore->query('SELECT rank FROM ' . $this->usersTable . ' WHERE id = ?', array($this->id));
			$this->rank = $data['rank'];
		}
	}
	public function login($username, $password) {
		$errored = false;
		$password = $this->imnicore->hash($password);
		if(empty($username) || empty($password)) {
			$errored = true;
			$msg[] = "Tous les champs doivent être remplis.";
		}
		$data = $this->imnicore->query('SELECT * FROM ' . $this->usersTable . ' WHERE username = ? AND password = ?', array($username, $password));
		if(empty($data)) {
			$errored = true;
			$msg[] = "Pseudo ou mot de passe incorrect.";
		}
		if(!$errored) {
			$this->setUserVar('id', $data['id']);
			$this->setUserVar('name', $data['username']);
			$this->setUserVar('email', $data['email']);
			$this->setUserVar('rank', $data['rank']);
			return array('success' => true);
		} else {
			return array('success' => false, 'msg' => $msg);
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
	
	private function initToken() {
		$temptoken = $this->imnicore->getToken(100);
		$this->imnicore->query('UPDATE ' . $this->usersTable . ' SET auth_ticket=? WHERE id=?', array($temptoken, $this->getId()));
		$this->token = $temptoken;
	}
	
	public function getToken() {
		if($this->token == NULL) {
			$this->initToken();
		}
		return $this->token;
	}
	public function getInfo($column) {
		$data = $this->imnicore->query('SELECT * FROM ' . $this->usersTable . ' WHERE id = ?', array($this->getId()));
		return $data[$column];
	}
	public function isAdmin() {
		if($this->rank >= 5) {
			return true;
		}
		return false;
	}
	public function getRank() {
		return $this->rank;
	}
	private function setUserVar($uvar, $uvalue) {
		$this->$uvar = $uvalue;
		$_SESSION[$uvar] = $uvalue;
	}
	public function disconnect() {
		$this->id = NULL;
		$this->name = NULL;
		$this->email = NULL;
		$this->rank = NULL;
		$this->token = NULL;
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
	public function register($username, $password, $repassword, $email, $dRank = 1) {
		$errored = false;
		$msg = array();
		if(!preg_match('/^[a-z\d_.-@ç!:\/;?.*%$£\(\)#{\[|\]]{5,30}$/i', $username)) {
			$errored = true;
			$msg[] = 'Votre pseudo doit faire entre 5 et 20 caractères.';
		}
		if(!empty($this->imnicore->query('SELECT * FROM ' . $this->usersTable . ' WHERE username = ?', array($username)))) {
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
		if(!empty($this->imnicore->query('SELECT * FROM ' . $this->usersTable . ' WHERE email = ?', array($email)))) {
			$errored = true;
			$msg[] = 'Votre adresse email est déjà utilisée.';
		}
		if(!$errored) {
			$hPassword = $this->imnicore->hash($password);
			$temptoken = $this->imnicore->getToken(100);
			$id = $this->imnicore->query('INSERT INTO ' . $this->usersTable . ' (`username`, `password`, `email`, `rank`, `auth_ticket`) VALUES (?, ?, ?, ?, ?)', array($username, $hPassword, $email, $dRank, $temptoken));
			$this->setUserVar('id', $id);
			$this->setUserVar('name', $username);
			$this->setUserVar('email', $email);
			$this->setUserVar('token', $temptoken);
			$this->setUserVar('rank', $dRank);
			return array('success' => true);
		} else {
			return array('success' => false, 'errors' => $msg);
		}
	}
	
	public function getCredits() {
		return $this->getInfo("credits");
	}
	public function getDiamonds() {
		return $this->getInfo('vip_points');
	}
	public function getDuckets() {
		return $this->getInfo('activity_points');
	}
}