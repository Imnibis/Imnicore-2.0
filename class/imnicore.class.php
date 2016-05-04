<?php
class Imnicore {
	private $path;
	private $db;
	private $query;
	private $data;
	public function __construct($dbtype, $host = "localhost", $dbuser = "root", $dbpassword = "", $dbname = "imnicore") {
		switch($dbtype) {
			case "mysql":
				try {
					$this->db = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $dbuser, $dbpassword);
				} catch(PDOException $e) {
					die("ERREUR PDO: " . $e->getMessage() . "<br/>");
				}
			break;
			case "pgsql":
				try {
					$this->db = new PDO('pgsql:host=' . $host . ';dbname=' . $dbname, $dbuser, $dbpassword);
				} catch(PDOException $e) {
					die("ERREUR PDO: " . $e->getMessage() . "<br/>");
				}
			break;
			default:
				echo 'ERREUR IMNICORE: LE TYPE DE DATABASE DOIT ÊTRE "mysql" OU "pgsql".';
				die();
		}
		$this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $this->db->exec("SET CHARACTER SET utf8");
		$this->query = $this->db->prepare('SELECT path FROM ic_settings');
		$this->query->execute();
		$this->data = $this->query->fetch(PDO::FETCH_OBJ);
		$this->path = $this->data->path;
	}
	public function getPath() {
		return $this->path;
	}
}
$imnicore = new Imnicore('mysql', 'localhost', 'root', 'Imniboss123', 'imnicore');
global $imnicore;