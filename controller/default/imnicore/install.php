<?php

#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#
#									#
#			  IMNICORE v2			#
#									#
#			  PAR IMNIBIS			#
#									#
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#

class Controller extends ControllerBase {
	private $after = '';
	public function run() {
		$this->redirectBadRequests();
		if(isset($_GET['lang'])) {
			$_SESSION['lang'] = $_GET['lang'];
			Lang::forceLang($_GET['lang']);
		} elseif(isset($_SESSION['lang'])) {
			Lang::forceLang($_SESSION['lang']);
		}
		$this->addTplVar('step', $this->getStep());
		switch($this->getStep()) {
			case 2:
				$this->addTplVar('langs', Imnicore::getLangs());
			break;
			default:
				// nothing.
			break;
		}
		if(isset($_GET['do']) && $_GET['do'] == "check") {
			switch($this->getStep()) {
				case 0:
					$_SESSION['step'] = 1;
					Imnicore::redirect(Imnicore::getPath() . '/imnicore/install/step1');
				case 1:
					$this->checkDB();
				break;
				case 2:
					$this->checkInfos();
				break;
				case 3:
					unset($_SESSION['step']);
					if(isset($_SESSION['lang'])) {
						unset($_SESSION['lang']);
					}
					Imnicore::setSetting('installed', '1');
					Imnicore::redirect(Imnicore::getPath());
				break;
				default:
					// nothing.
				break;
			}
		}
	}
	public function authorize() {
		Imnicore::isAuthorized('*', true);
	}
	private function redirectBadRequests() {
		if(Imnicore::installed()) {
			Imnicore::redirect(Imnicore::getPath());
		}
		if(isset($_SESSION['step']) && $_SESSION['step'] != $this->getStep()) {
			Imnicore::redirect(Imnicore::getPath() . '/imnicore/install/step' . $_SESSION['step']);
		}
		if(!isset($_SESSION['step']) && $this->getStep() != 0) {
			Imnicore::redirect(Imnicore::getPath() . '/imnicore/install');
		}
	}
	private function getStep() {
		return (isset($_GET['step'])) ? preg_replace('#step([0-9]+)#', '$1', $_GET['step']) : 0;
	}
	private function checkDB() {
		$errored = false;
		if(empty($_POST['host']) || empty($_POST['user']) || empty($_POST['password']) || empty($_POST['dbname'])) {
			$errored = true;
			$msg = Lang::get('form.error.empty');
		} else {
			try {
				$db = new Database($_POST['host'], $_POST['user'], $_POST['password'], $_POST['dbname']);
			} catch(PDOException $e) {
				$errored = true;
				$msg = Lang::get('install.database.error.pdo', $e->getMessage());
			}
		}
		if(!$errored) {
			$vars = array('database' => array(
					'host' => $_POST['host'],
					'user' => $_POST['user'],
					'password' => $_POST['password'],
					'name' => $_POST['dbname']
			));
			$json = json_encode($vars, JSON_PRETTY_PRINT);
			$file = fopen('settings.json', 'w');
			fwrite($file, $json);
			fclose($file);
			$db = Imnicore::getDB();
			$db->query('CREATE TABLE IF NOT EXISTS `ic_settings` ( `id` INT NOT NULL AUTO_INCREMENT , `name` TEXT NOT NULL , `value` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;');
			$db->query('CREATE TABLE IF NOT EXISTS `ic_user_settings` (`id` INT NOT NULL AUTO_INCREMENT , `uid` INT NOT NULL , `name` TEXT NOT NULL , `value` TEXT NOT NULL , PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=UTF8;');
			Imnicore::setSetting('path', Imnicore::getRelativePath());
			Imnicore::setSetting('lang', 'fr');
			Imnicore::setSetting('theme', 'default');
			Imnicore::setSetting('installed', '0');
			$_SESSION['step'] = 2;
			Imnicore::redirect(Imnicore::getPath() . '/imnicore/install/step2');
		}
		$this->addTplVar('errorMsg', $msg);
	}
	private function checkInfos() {
		if(empty($_POST['URL']) || empty($_POST['name']) || empty($_POST['defaultLang']) || empty($_POST['usersTable'])) {
			$errored = true;
			$msg = Lang::get('form.error.empty');
			$this->addTplVar('errorMsg', $msg);
		} else {
			$path = preg_replace('#([\\/]*)$#', '', $_POST['URL']);
			Imnicore::setSetting('path', $path);
			Imnicore::setSetting('lang', $_POST['defaultLang']);
			Imnicore::setSetting('name', $_POST['name']);
			Imnicore::usersTable($_POST['usersTable']);
			if(!isset($_POST['tableExists'])) {
				Imnicore::getDB()->query('CREATE TABLE `' . $_POST['usersTable'] . '` ( `id` INT NOT NULL AUTO_INCREMENT , `username` VARCHAR(255) NOT NULL , `password` VARCHAR(255) NOT NULL , `email` VARCHAR(255) NOT NULL , `rank` INT NOT NULL , `auth_ticket` VARCHAR(100) NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;');
			}
			$_SESSION['step'] = 3;
			Imnicore::redirect(Imnicore::getPath() . '/imnicore/install/step3');
		}
	}
}