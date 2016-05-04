<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

##
#
# VARIABLE PATH TEMPORAIRE
#
##

$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$file = '/core.php';
$url = str_replace($file, '', $url);
$path = htmlspecialchars(rtrim($url, '/'), ENT_QUOTES, 'UTF-8');

##
#
# INCLUDES BASIQUES
#
##

include($path . '/includes/session.inc.php');

##
#
# INCLUSION DES CLASS
#
##

include($path . '/class/imnicore.class.php');
include($path . '/class/user.class.php');

##
#
# NETTOYAGE DE LA VARIABLE PATH
#
##

$path = NULL;

##
#
# CREATION DES OBJETS
#
##

$imnicore = new Imnicore('mysql', 'localhost', 'root', 'Imniboss123', 'imnicore');
$user = new User();