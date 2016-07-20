<?php
class Imnicore {
	private $relativePath;
	private $page;
	private $name;
	private $path;
	private $db;
	private $req;
	private $data;
	private $lang;
	
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
		$this->req = $this->db->prepare('SELECT * FROM ic_settings WHERE name=? Or name=?');
		$this->req->execute(array('path', 'name'));
		for ($i = 0; $i < 2; $i++ ) {
			$this->data = $this->req->fetch(PDO::FETCH_ASSOC);
			if ($this->data['name'] == 'name') {
				$this->name = $this->data['value'];
			} elseif ($this->data['name'] == 'path') {
				$this->path = $this->data['value'];
			}
		}
		$this->relativePath = preg_replace('#((http:\/\/|https:\/\/)(www.)?(([a-zA-Z0-9-]){2,}\.){1,9}([a-zA-Z]){2,6})#', '', $this->path);
		if($this->relativePath == '/') {
			$this->relativePath = '';
		}
		$this->lang = $this->getSetting('lang');
	}
	
	public function getUsersTable() {
		if($this->getSetting('emulator') == "comet") {
			return "players";
		}
		else {
			return "users";
		}
	}
	
	public function getLocale() {
		return $this->lang;
	}
	
	public function startsWith($haystack, $needle) {
		return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
	}

	public function endsWith($haystack, $needle) {
		return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
	}
	
	public function getLangVars() {
		$file = file_get_contents($this->getRelativePath() . 'lang/' . $this->getLocale() . '.lang');
		rtrim(PHP_EOL, $file);
		$vars = explode(PHP_EOL, $file);
		foreach($vars as $k) {
			if(is_array($k)) {
				$k = explode('=', $k);
				$data[$k[0]] = $k[1];
			}
		}
		return $data;
	}
	
	public function getPath() {
		return $this->path;
	}
	
	public function getPresetPath($str) {
		$presetPath = $this->getRelativePath() . 'presets/' . $this->getSetting('theme') . '/' . $str . '.html';
		return $presetPath;
	}
	
	public function setPage($strPage) {
		$this->page = $strPage;
	}
	
	public function getPageID() {
		return $this->page;
	}
	
	public function getRelativePath() {
		return $this->relativePath;
	}
	
	
	public function getName() {
		return $this->name;
	}
	
	public function getSetting($setting) {
		$data = $this->query('SELECT * FROM ic_settings WHERE name=?', array($setting));
		return $data['value'];
	}
	
	public function checkPage($page) {
		if(file_exists($this->getRelativePath() . 'controllers/' . $this->getSetting('theme') . '/' . $page . '.php') && file_exists($this->getRelativePath() . 'themes/' . $this->getSetting('theme') . '/' . $page . '.html')) {
			return true;
		} else {
			return false;
		}
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
	
	// DEPRECATED
	
	/*public function assignTemplate($page, $vars = array()) {
		$theme = $this->query('SELECT theme from ic_settings');
		$theme = $theme['theme'];
		$templatePath = $this->getPath() . 'themes/' . $theme . '/' . $page . '.html';
		if($theme != "default" && !file_exists($templatePath)) {
			$templatePath = $this->getPath() . 'themes/default/' . $page . '.html';
		}
		$template = file_get_contents($templatePath);
		$processedTemplate = '?>' . preg_replace('#{{(.+)}}#', '<?php echo $${1}; ?>', preg_replace('#\(\((.+)\)\)#', '<?php ${1}; ?>', $template)) . '<?php';
		return $processedTemplate;
	}*/
	
	
	public function hash($password) {
		return sha1(md5($password . 'ze6e21zeez6gzef15ze68f'));
	}
	
	
	public function getController() {
		$controllerPath = $this->getRelativePath() . 'controllers/' . $this->getSetting('theme') . '/' . $this->getPageID() . '.php';
		return $controllerPath;
	}
	
	public function crypto_rand_secure($min, $max)
	{
		$range = $max - $min;
		if ($range < 1) return $min; // not so random...
		$log = ceil(log($range, 2));
		$bytes = (int) ($log / 8) + 1; // length in bytes
		$bits = (int) $log + 1; // length in bits
		$filter = (int) (1 << $bits) - 1; // set all lower bits to 1
		do {
			$rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
			$rnd = $rnd & $filter; // discard irrelevant bits
		} while ($rnd >= $range);
		return $min + $rnd;
	}
	
	public function getToken($length)
	{
		$token = "";
		$codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
		$codeAlphabet.= "0123456789";
		$max = strlen($codeAlphabet) - 1;
		for ($i=0; $i < $length; $i++) {
			$token .= $codeAlphabet[$this->crypto_rand_secure(0, $max)];
		}
		return $token;
	}
}