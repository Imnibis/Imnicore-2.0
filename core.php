<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

##
#
# VARIABLE PATH TEMPORAIRE
#
##

set_include_path('/var/www/dev');
$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$file = '/core.php';
$url = str_replace($file, '', $url);
$path = htmlspecialchars(rtrim($url, '/'), ENT_QUOTES, 'UTF-8');
$relativepath = str_replace('http://' . $_SERVER['HTTP_HOST'], '', $path);

##
#
# INCLUDES BASIQUES
#
##

require($path . '/includes/session.inc.php');

##
#
# INCLUSION DES CLASS
#
##

require($relativepath . '/class/imnicore.class.php');
require($relativepath . '/class/user.class.php');



echo $imnicore->getPath();