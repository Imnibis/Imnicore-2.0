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
		$this->addTplVar('step', $this->getStep());
		switch($this->getStep()) {
			case 3:
				$this->addTplVar('langs', Imnicore::getLangs());
			break;
			default:
				// nothing.
			break;
		}
		if(isset($_GET['do']) && $_GET['do'] == "check") {
			switch($this->getStep()) {
				case 2:
					$this->checkDB();
				break;
				case 3:
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
			$json = json_encode($vars);
			$file = fopen('settings.json', 'w');
			fwrite($file, $json);
			Imnicore::redirect('imnicore/install/step3');
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
			Imnicore::redirect('imnicore/install/step4');
		}
	}
	private function delete() {
		Imnicore::rmdir('controller/imnicore/');
		Imnicore::rmdir('css/imnicore/');
		Imnicore::rmdir('js/imnicore/');
		Imnicore::redirect(Imnicore::getPath());
	}
}