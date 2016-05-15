<?php
$errored = false;
if(empty($_POST) || empty($_POST['username']) || empty($_POST['password']) || empty($_POST['repassword']) || empty($_POST['email'])) {
	$errored = true;
	$msg[] = 'Veuillez remplire tous les champs.';
}
$result = '';
if(!$errored) {
	$result = $user->register($_POST['username'], $_POST['password'], $_POST['repassword'], $_POST['email']);
	if(!$result['success']) {
		$errored = true;
		$msg = $result['errors'];
	}
}
if($errored) {
	$strMsg = implode('|', $msg);
	$template = $imnicore->assignTemplate('register/submit');
	eval($template);
} else {
	header('Location: ' . $imnicore->getPath());
	exit;
}