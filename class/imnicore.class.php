<?php
class Imnicore {
	private $path;
	public function __construct($dbtype, $host = "localhost", $dbuser = "root", $dbpassword = "", $dbname = "imnicore") {
		switch($dbtype) {
			case "mysql":
				try {
					$db = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $dbuser, $dbpassword);
				} catch(PDOException $e) {
					print "ERREUR PDO: " . $e->getMessage() . "<br/>";
					die();
				}
			break;
			case "pgsql":
				try {
					$db = new PDO('pgsql:host=' . $host . ';dbname=' . $dbname, $dbuser, $dbpassword);
				} catch(PDOException $e) {
					print "ERREUR PDO: " . $e->getMessage() . "<br/>";
					die();
				}
			break;
			default:
				echo 'ERREUR IMNICORE: LE TYPE DE DATABASE DOIT ÊTRE "mysql" OU "pgsql".';
				die();
		}
		$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $db->exec("SET CHARACTER SET utf8");
		$query = $db->prepare('SELECT path FROM imnicore_settings');
		$query->exec();
		$pathReq = $query->fetch(PDO::FETCH_OBJ);
		$path = $pathReq->path;
	}
}