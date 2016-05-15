<?php
$errorMsg = "";
if(isset($_POST['error'])) {
	$msg = explode('|', htmlspecialchars($_POST['msg']));
	$errorMsg = '<div id="error">';
	foreach($msg as $k) {
		$errorMsg = $errorMsg . $k .'<br>';
	}
	$errorMsg = $errorMsg . '</div>';
}
$imnicore->assignTemplate('register', array('errorMsg' => $errorMsg));