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
		
		$this->addTplVar('errored', "false");
		if(!isset($_GET['file']) || !isset($_GET['ext'])) {
			$this->addTplVar('errored', "GET var missing");
			return false;
		}
		if(!file_exists('img/' . Imnicore::getTheme() . '/'. $_GET['file'] . '.' . $_GET['ext'])) {
			$this->addTplVar('errored', "unknown file " . $_GET['file'] . '.' . $_GET['ext']);
			return false;
		}
		$this->displayImg();
	}
	public function authorize() {
		Imnicore::isAuthorized('*', true);
	}
	private function displayImg() {
		$path = 'img/' . Imnicore::getTheme() . '/' . $_GET['file'] . '.' . $_GET['ext'];
		$imginfo = getimagesize($path);
		header('Content-Type: ' . $imginfo['mime']);
		readfile($path);
	}
}