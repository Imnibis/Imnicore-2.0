<?php

##
#
# AFFICHAGE DES ERREURS
#
##

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

##
#
# VARIABLE PATH TEMPORAIRE
#
##

$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$file = '/index.php';
$url = str_replace($file, '', $url);
$path = htmlspecialchars(rtrim($url, '/'), ENT_QUOTES, 'UTF-8');
$relativepath = str_replace('http://' . $_SERVER['HTTP_HOST'], '', $path);

##
#
# INCLUSION DU CORE.PHP
#
##
require_once('' . $relativepath . '/core.php');