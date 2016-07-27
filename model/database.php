<?php

#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#
#									#
#			 IMNICORE 2.0			#
#									#
#			  PAR IMNIBIS			#
#									#
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#

class Database { 
	private $db;	
	public function __construct($host, $dbuser, $dbpassword, $dbname) {
		try {
			$this->db = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $dbuser, $dbpassword);
		} catch(PDOException $e) {
			throw $e;
		}
		$this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$this->db->query("SET CHARACTER SET utf8");
	}
	public function query($query, $vars = NULL, $multiple = false) {
		$req = $this->db->prepare($query);
		$req->execute($vars);
		if(preg_match('#SELECT#', $query)) {
			if($multiple) {
				$data = $req->fetchAll();
			} else {
				$data = $req->fetch();
			}
			return $data;
		} elseif(preg_match('#INSERT#', $query)) {
			return $this->db->lastInsertId();
		}
	}
}