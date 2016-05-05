<?php
session_start();
##
#
# MODIFICATIONS DES PARAMETRES PHP
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
$file = '/core.php';
$file2 = '/index.php';
$url = str_replace($file, '', $url);
$url = str_replace($file2, '', $url);
$path = htmlspecialchars(rtrim($url, '/'), ENT_QUOTES, 'UTF-8');
$relativePath = preg_replace('#((http:\/\/|https:\/\/)(www.)?(([a-zA-Z0-9-]){2,}\.){1,9}([a-zA-Z]){2,6}(\/?))#', '', $path);

##
#
# VERIFICATION DE L'INSTALLATION
#
##

/*if(!file_exists($relativepath . '/includes/database.inc.php')) {
	header('Location: ' . $relativepath . '/install');
	exit;
}*/

##
#
# INCLUSION DES CLASS
#
##

require($relativepath . '/class/imnicore.class.php');
require($relativepath . '/class/user.class.php');

##
#
# CREATION DES OBJETS
#
##

$imnicore = new Imnicore('localhost', 'root', 'Imniboss123', 'imnicore');
$user = new User();