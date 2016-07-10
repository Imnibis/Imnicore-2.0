<?php 

#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#
#									#
#			   IMNICORE				#
#									#
#			  PAR IMNIBIS			#
#									#
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#

class Template {
	private $tpl;
	private $tplPath = NULL;
	private $tplFile = NULL;
	public function __construct() {
		require('smarty/libs/Autoloader.php');
		Smarty_Autoloader::register();
		$this->tpl = new Smarty();
	}
	public function addTplVar($var, $value) {
		$this->tpl->assign($var, $value);
	}
	public function displayTpl($path = NULL, $file = NULL) {
		if($this->tplPath != NULL) {
			$path = $this->tplPath();
		}
	}
	public function setTplPath($path) {
		$this->tplPath = $path;
	}
}