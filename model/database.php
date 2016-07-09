<?php
class Database {
	private $db;
	/**
	 * Opens connection to database
	 * @param $host
	 * @param $dbuser
	 * @param $dbpassword
	 * @param $dbname
	 */
	public function __construct($host, $dbuser, $dbpassword, $dbname) {
		try {
			$this->db = new PDO ( 'mysql:host=' . $host . ';dbname=' . $dbname, $dbuser, $dbpassword );
		} catch ( PDOException $e ) {
			die ( "<b>IMNICORE //</b> ERREUR PDO: " . $e->getMessage () . "<br/>" );
		}
		$this->db->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$this->db->query ( "SET CHARACTER SET utf8" );
	}
	/**
	 * Execute a query
	 * @param $query
	 * @param $vars
	 * @param $multiple
	 */
	public function query($query, $vars = NULL, $multiple = false) {
		$req = $this->db->prepare ( $query );
		$req->execute ( $vars );
		if (preg_match ( '#SELECT#', $query )) {
			if ($multiple) {
				$data = $req->fetchAll ();
			} else {
				$data = $req->fetch ();
			}
			return $data;
		} elseif (preg_match ( '#INSERT#', $query )) {
			return $this->db->lastInsertId ();
		}
	}
}