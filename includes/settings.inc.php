<?php
$query = $db->prepare('SELECT * FROM imnicore_settings');
$query->execute();
$settings = $query->fetch(PDO::FETCH_OBJ);