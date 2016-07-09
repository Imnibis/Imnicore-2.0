<?php
require('inc/errorHandler.php');
require('init.php');
$imnicore = new Imnicore();
$db = $imnicore->init();
echo $db->query('SELECT * FROM ic_settings')['path'];