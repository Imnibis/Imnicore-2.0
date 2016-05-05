<?php
class Imnicore {
	private $relativePath;
	private $path;
	private $db;
	private $req;
	private $data;
	public function __construct($host = "localhost", $dbuser = "root", $dbpassword = "", $dbname = "imnicore", $dbtype = "mysql") {
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
		$this->req = $this->db->prepare('SELECT path FROM ic_settings');
		$this->req->execute();
		$this->data = $this->req->fetch(PDO::FETCH_OBJ);
		$this->path = $this->data->path;
		$this->relativePath = preg_replace('#((http:\/\/|https:\/\/)(www.)?(([a-zA-Z0-9-]){2,}\.){1,9}([a-zA-Z]){2,6}(\/?))#', '', $this->path);
	}
	public function getPath() {
		return $this->path;
	}
	public function getRelativePath() {
		return $this->relativePath;
	}
	public function query($query, $vars = NULL, $multiple = false) {
		$this->req = $this->db->prepare($query);
		$this->req->execute($vars);
		if(preg_match('#SELECT#', $query)) {
			if($multiple) {
				$this->data = $this->req->fetchAll();
			} else {
				$this->data = $this->req->fetch();
			}
			return $this->data;
		} elseif(preg_match('#INSERT#', $query)) {
			return $this->db->lastInsertId();
		}
	}
	public function getTemplate($page) {
		$theme = $this->query('SELECT theme from ic_settings');
		$theme = $theme['theme'];
		$templatePath = $this->getPath() . '/themes/' . $theme . '/' . $page . '.tpl';
		$template = file_get_contents($templatePath);
		$processedTemplate = '?>' . preg_replace('#{{(.+)}}#', '<?php echo $${1}; ?>', $template) . '<?php';
		return $processedTemplate;
	}
	public function getPage($page) {
		$this->data = $this->query('SELECT * FROM ic_pages WHERE pageid = ?', array($page));
		if(empty($this->data)) {
			$this->data['controllerPath'] = '/controllers/404.php';
		}
		$this->data['controllerPath'] = $this->getRelativePath() . '/controllers/' . $page . '.php';
		return $this->data['controllerPath'];
	}
}
