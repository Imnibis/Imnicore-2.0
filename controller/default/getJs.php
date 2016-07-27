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
		header('Content-Type: text/javascript');
		$this->addTplVar('errored', "false");
		if(!isset($_GET['file'])) {
			$this->addTplVar('errored', "true");
			return false;
		}
		if(!$this->getFile($_GET['file'])) {
			$this->addTplVar('errored', "true");
		}
		$script = $this->parse($this->getFile($_GET['file']));
		DynScripts::processLangVars($script);
		$this->addTplVar('script', $script);
	}
	public function authorize() {
		Imnicore::isAuthorized('*', true);
	}
	private function getFile($file) {
		$file = 'js/' . Imnicore::getTheme() . '/' . $file . '.js';
		return (file_exists($file)) ? file_get_contents($file) : false;
	}
	private function parse($script) {
		return preg_replace(DynScripts::getVars(), DynScripts::getReplacements(), $script);
	}
}