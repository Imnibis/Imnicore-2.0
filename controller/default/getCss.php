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
		header('Content-Type: text/css');
		$this->addTplVar('errored', "false");
		if(!isset($_GET['file'])) {
			$this->addTplVar('errored', "no get argument");
			return false;
		}
		if(!$this->getFile($_GET['file'])) {
			$this->addTplVar('errored', "file dont exist");
		}
		$script = $this->parse($this->getFile($_GET['file']));
		DynScripts::processLangVars($script);
		$this->addTplVar('script', $script);
	}
	public function authorize() {
		Imnicore::isAuthorized('*', true);
	}
	private function getFile($file) {
		$file = 'css/' . Imnicore::getTheme() . '/' . $file . '.css';
		return (file_exists($file)) ? file_get_contents($file) : false;
	}
	private function parse($script) {
		return preg_replace(DynScripts::getVars(), DynScripts::getReplacements(), $script);
	}
}