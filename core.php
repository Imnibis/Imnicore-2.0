<?php

##
#
# MODIFICATIONS DES PARAMETRES PHP
#
##

header('Access-Control-Allow-Origin: *');
ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

if(!isset($_SESSION)) {
	$_SESSION = array();
}

##
#
# VARIABLE PATH TEMPORAIRE
#
##

$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$url = explode('/', $url);
array_pop($url);
$url = implode('/', $url);
$path = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
$relativePath = preg_replace('#((http:\/\/|https:\/\/)(www.)?(([a-zA-Z0-9-]){2,}\.){1,9}([a-zA-Z]){2,6})#', '', $path);
if($relativePath == '/') {
	$relativePath = '';
}

##
#
# INCLUSIONS
#
##

require($relativePath . 'ic.settings.php');

##
#
# INCLUSION DES CLASS
#
##

require($relativePath . 'class/imnicore.class.php');
$imnicore = new Imnicore($dbhost, $dbuser, $dbpassword, $dbname);
unset($dbhost);
unset($dbuser);
unset($dbpassword);
unset($dbname);
require($relativePath . 'class/user.class.php');
$user = new User($imnicore, $_SESSION);

$lang = $imnicore->getLangVars();

##
#
# INITIALISATION DE SMARTY
#
##

require_once($imnicore->getRelativePath() . 'smarty/libs/Autoloader.php');
Smarty_Autoloader::register();
$tpl = new Smarty();
$tpl->assign('imnicore', $imnicore);
$tpl->assign('user', $user);
$tpl->assign('lang', $lang);