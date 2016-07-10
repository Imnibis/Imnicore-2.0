<?php

#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#
#									#
#			   IMNICORE				#
#									#
#			  PAR IMNIBIS			#
#									#
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#

class ControllerBase {
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
		return true;
	}
	public function addTplVars($vars) {
		foreach($vars as $k => $v) {
			$this->addTplVar($k, $v);
		}
	}
	public function displayTpl($path = NULL, $file = NULL) {
		if($this->tplPath != NULL) {
			$path = $this->tplPath();
		}
		if($this->tplFile != NULL) {
			$file = $this->tplFile();
		}
		$this->tpl->setTemplateDir($path);
		$this->tpl->display($file . '.html');
		return true;
	}
	public function setTplPath($path) {
		$this->tplPath = $path;
		return true;
	}
}