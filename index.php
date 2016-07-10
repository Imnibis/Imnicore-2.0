<?php

#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#
#									#
#			   IMNICORE				#
#									#
#			  PAR IMNIBIS			#
#									#
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#

require('inc/errorHandler.php');
require('init.php');
$db = Imnicore::init();
echo $db->query('SELECT * FROM ic_settings')['path'];