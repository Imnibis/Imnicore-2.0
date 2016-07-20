<?php

session_start();

##
#
# AFFICHAGE DES ERREURS
#
##

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

// Remplacez le chemin ci-dessous par le chemin absolu menant à la racine du serveur web.
$includePath = "/chemin/vers/la/racine/du/serveur/web";
set_include_path($includePath);

##
#
# VARIABLE PATH TEMPORAIRE
#
##

$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$file = 'index.php';
$url = str_replace($file, '', $url);
$path = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');
$relativepath = str_replace('http://' . $_SERVER['HTTP_HOST'] . '/', '', $path);
if($relativepath == '/') {
	$relativepath = '';
}

##
#
# INCLUSION DU CORE.PHP
#
##

require($relativepath . 'core.php');

##
#
# NETTOYAGE DE LA VARIABLE $path
#
##

unset($path);
unset($relativepath);

##
#
# RECUPERATION DE LA PAGE ACTUELLE
#
##

if(!isset($_GET['page'])) {
	$page = "index";
} else {
	$page = $_GET['page'];
}
$imnicore->setPage($page);
$pagePath = explode('/', $imnicore->getPageID());
$pageName = array_pop($pagePath);
$tplPath = $imnicore->getRelativePath() . 'themes/' . $imnicore->getSetting('theme') .'/' . implode('/', $pagePath);
$tplPath404 = $imnicore->getRelativePath() . 'themes/' . $imnicore->getSetting('theme') .'/';

if($imnicore->checkPage($page)) {
	require($imnicore->getController());
	$tpl->setTemplateDir($tplPath);
	$tpl->display($pageName . '.html');
} else {
	$imnicore->setPage('404');
	require($imnicore->getController());
	$tpl->setTemplateDir($tplPath404);
	$tpl->display('404.html');
}