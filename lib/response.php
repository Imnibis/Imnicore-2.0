<?php 

#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#
#									#
#			 IMNICORE 2.0			#
#									#
#			  PAR IMNIBIS			#
#									#
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#

class Response {
	public function __construct() {
		if(!$this->checkPage()) {
			Imnicore::is404(true);
		}
		$this->display();
	}
	private function checkPage():bool {
		return (file_exists('controller/' . Imnicore::getTheme() . '/' . Imnicore::getPageID() . '.php') && file_exists('view/' . Imnicore::getTheme() . '/' . Imnicore::getPageID() . '.html'));
	}
	private function checkInstall():bool {
		if(!Imnicore::installed() && !Imnicore::isAuthorized('install')) {
			Imnicore::redirect(Imnicore::getRelativePath() . '/imnicore/install');
		}
		return true;
	}
	private function display():bool {
		require('controller/' . Imnicore::getTheme() . '/' . Imnicore::getPageID() . '.php');
		$controller = new Controller();
		$controller->authorize();
		$this->checkInstall();
		$controller->run();
		$controller->displayTpl('view/' . Imnicore::getTheme() . '/', Imnicore::getPageID());
		return true;
	}
}