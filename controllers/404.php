<?php
// INIT DU CONTROLLER
/*$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$file = '/index.php';
$url = str_replace($file, '', $url);
$path = htmlspecialchars(rtrim($url, '/'), ENT_QUOTES, 'UTF-8');
$relativepath = str_replace('http://' . $_SERVER['HTTP_HOST'] . '/', '', $path);
require($relativepath . '/core.php');*/
// FIN DE L'INIT DU CONTROLLER

$text = '404';

// AFFICHAGE DU TEMPLATE
$template = $imnicore->getTemplate('404');
eval($template);