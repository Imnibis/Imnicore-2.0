<?php
$text = 'TEST REUSSI!';
$connectText = 'kk';
if($user->isConnected()) {
	$connectText = 'Vous etes connecte en tant que ' . $user->getName();
}
// AFFICHAGE DU TEMPLATE
$imnicore->assignTemplate('index', array('text' => $text, 'connectText' => $connectText));