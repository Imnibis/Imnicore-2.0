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
set_include_path('/var/www/dev');

##
#
# VARIABLE PATH TEMPORAIRE
#
##

$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$file = 'index.php';
$url = str_replace($file, '', $url);
$path = htmlspecialchars(rtrim($url, '/'), ENT_QUOTES, 'UTF-8');
$relativepath = str_replace('http://' . $_SERVER['HTTP_HOST'] . '/', '', $path);
$relativepath = rtrim($relativepath, '/');

##
#
# INCLUSION DU CORE.PHP
#
##

require($relativepath . '/core.php');

##
#
# NETTOYAGE DE LA VARIABLE $path
#
##

$path = NULL;

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

$pagePath = $imnicore->getPage($page);

require($pagePath);