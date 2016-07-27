<?php

#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#
#									#
#			  IMNICORE v2			#
#									#
#			  PAR IMNIBIS			#
#									#
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#

class Controller extends ControllerBase {
	public function run() {
		$this->redirectBadRequests();
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
				default:
					// nothing.
				break;
			}
		} elseif(isset($_GET['do']) && $_GET['do'] == "delete") {
			$this->delete();
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
		if(!isset($_POST['host']) || !isset($_POST['user']) || !isset($_POST['password']) || !isset($_POST['dbname'])) {
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
		if(!isset($_POST['URL']) || !isset($_POST['name']) || !isset($_POST['defaultLang'])) {
			$errored = true;
			$msg = Lang::get('form.error.empty');
			$this->addTplVar('errorMsg', $msg);
		} else {
			$db = Imnicore::getDB();
			$db->query('INSERT INTO ic_settings (`id`, `name`, `value`) VALUES (NULL, "path", ?)', array($_POST['path']));
			$db->query('INSERT INTO ic_settings (`id`, `name`, `value`) VALUES (NULL, "name", ?)', array($_POST['name']));
			$db->query('INSERT INTO ic_settings (`id`, `name`, `value`) VALUES (NULL, "lang", ?)', array($_POST['defaultLang']));
			$_SESSION['step'] = 3;
			Imnicore::redirect(Imnicore::getPath() . '/imnicore/install/step3');
		}
	}
	private function delete() {
		Imnicore::rmdir(Imnicore::getRelativePath() . '/controller/imnicore/');
		Imnicore::rmdir(Imnicore::getRelativePath() . '/view/imnicore/');
		Imnicore::rmdir(Imnicore::getRelativePath() . '/css/imnicore/');
		Imnicore::rmdir(Imnicore::getRelativePath() . '/js/imnicore/');
		unset($_SESSION['step']);
		
		Imnicore::redirect(Imnicore::getPath());
	}
}