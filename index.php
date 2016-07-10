<?php

#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#
#									#
#			   IMNICORE				#
#									#
#			  PAR IMNIBIS			#
#									#
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#

require('inc/errorHandler.php');
require('imnicore.php');
$config = Imnicore::init();
$db = $config['database'];
echo Imnicore::getSetting('path');