<?php

#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#
#									#
#			 IMNICORE 2.0			#
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
	public function addTplVar($var, $value):bool {
		$this->tpl->assign($var, $value);
		return true;
	}
	public function addTplVars($vars) {
		foreach($vars as $k => $v) {
			$this->addTplVar($k, $v);
		}
	}
	public function authorize() {
		
	}
	public function displayTpl($path = NULL, $file = NULL):bool {
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
	public function setTplPath($path):bool {
		$this->tplPath = $path;
		return true;
	}
}