﻿<?php
$url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$file = '/install';
$url = str_replace($file, '', $url);
$path = htmlspecialchars(rtrim($url, '/'), ENT_QUOTES, 'UTF-8');
$relativepath = str_replace('http://' . $_SERVER['HTTP_HOST'], '', $path);
$relativepath = htmlspecialchars(rtrim($relativepath, '/'), ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>IMNICORE ~ INSTALLATION</title>
	</head>
	<body>
		Bienvenue sur l'assistant d'installation d'IMNICORE. Il va vous guider pour configurer votre site.<br>
		<?php
			function apache_module_exists($module)
			{
				return in_array($module, apache_get_modules());
			}
			$rewrite = true;
			$chmod = true;
			$curl = true;
			$erreur = false;
			if(!apache_module_exists('mod_rewrite')) {
				$rewrite = false;
				$erreur = true;
			}
			if(!function_exists('curl_version')) {
				$curl = false;
				$erreur = true;
			}
			if(!is_writable('../includes')) {
				$chmod = false;
				$erreur = true;
			}
			if(!$rewrite) {
				echo 'Le module mod_rewrite n\'est pas activé. Il est nécessaire pour faire tourner IMNICORE. Veuillez l\'activer ou changer d\'hébergeur.<br>';
			}
			if(!$curl) {
				echo 'Le module mod_curl n\'est pas activé. Il est nécessaire pour faire tourner IMNICORE. Veuillez l\'activer ou changer d\'hébergeur.<br>';
			}
			if(!$chmod) {
				echo 'PHP ne peut pas écrire dans le dossier includes, veuillez définir son chmod à 777 pour continuer.<br>';
			}
			if(!$erreur) {
				echo 'Veuillez cliquer sur le bouton pour continuer l\'installation.<br>';
			}
		?>
		<?php if(!$erreur) { ?><a href="<?php echo $relativepath; ?>/install/step1.php"><?php } ?><button<?php if($erreur) { ?> disabled<?php } ?>>Continuer!</button><?php if(!$erreur) { ?></a><?php } ?>
	</body>
<html>