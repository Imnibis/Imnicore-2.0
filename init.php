<?php
session_start();
require('model/database.php');
class Imnicore {
	public function __construct() {
		// TODO: Do something when Imnicore inits.
	}
	
	public function init() {
		$json = file_get_content('settings.json');
		$config = json_decode($json, true);
		$db = new Database();
		yvdbiusonsdo
	}
}