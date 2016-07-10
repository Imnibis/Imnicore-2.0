<?php 

#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#
#									#
#			   IMNICORE				#
#									#
#			  PAR IMNIBIS			#
#									#
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#

class Response {
	private $page = NULL;
	public function __construct() {
		$this->is404(($this->checkPage()));
		echo $this->is404();
		require('controller/' . Imnicore::getTheme() . '/' . $this->getPageID() . '.php');
		$controller = new Controller();
		$controller->displayTpl('view/' . Imnicore::getTheme() . '/', $this->getPageID());
	}
	public function getPageID($override = false) {
		return ($this->page == NULL || $override) ? (isset($_GET['page']) ? $_GET['page'] : 'index') : $this->page;
	}
	private function is404($bool = NULL) {
		switch($bool) {
			case true:
				$this->page = '404';
			break;
			case false:
				$this->page = $this->getPageID(true);
			break;
			case NULL:
				return ($this->getPageID() == '404');
			break;
			default:
				return false;
			break;
		}
	}
	private function checkPage() {
		return (file_exists('controller/' . Imnicore::getTheme() . '/' . $this->getPageID() . '.php') && file_exists('view/' . Imnicore::getTheme() . '/' . $this->getPageID() . '.html'));
	}
}