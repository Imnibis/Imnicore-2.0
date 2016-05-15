<?php
$errorMsg = '';
if(isset($_POST['error'])) {
	$errorMsg = '<div id="error">Pseudo ou mot de passe incorrect.</div>';
}
###
$imnicore->assignTemplate('register', array('errorMsg' => $errorMsg));
###