<?php
require_once('init.php');
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
#											#|
#					Imnicore				#|
#											#|
#      			  par Imnibis				#|
#											#|
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|

// Pour ajouter une page, ajoutez-la dans le switch si-dessous.
// Dans le cas d'une page plus complexe (variables $_GET, par exemple),
// manipulez le .htaccess pour avoir un résultat concluant.

if(isset($_GET['page'])) {
$page = $_GET['page'];
} else {
	$page = "index";
}
switch($page) {
	case "index":
		//chemin vers la vue de l'index
		include('view/index.php');
	break;
	case "dossier/fichier":
		include('view/exemple.php');
	break;
	default:
		include('view/404.php');
	break;
}