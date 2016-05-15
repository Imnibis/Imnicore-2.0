<?php

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

require($relativepath . '/twig/Autoloader.php');
require($relativepath . '/class/imnicore.class.php');
require($relativepath . '/class/user.class.php');
$user = new User($imnicore, $_SESSION);

##
#
# INCLUSION DES PRESETS
#
##

/*if($imnicore->getSetting('usePresets') == 1) {
	foreach(scandir('./') as $k) {
		if(preg_match('/preset(.+)\.php/', $k)) {
			$k = preg_replace('/preset(.+)\.php/', '$1', $k);
			require($imnicore->getRelativePath() . '/preset' . $k . '.php');
			$getPreset = file_get_contents($imnicore->getPath() . '/themes/' . $imnicore->getSetting('theme') . '/preset' . $k . '.html');
			$blownPreset = explode('{!}', $getPreset);
			$preset[$k][0] = preg_replace('#{{(.+)}}#', '<?php echo $${1}; ?>', $blownPreset[0]);
			$preset[$k][1] = preg_replace('#{{(.+)}}#', '<?php echo $${1}; ?>', $blownPreset[1]);
		}
	}
}*/