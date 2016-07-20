<?php
$host = "localhost"; // Host
$dbname = "site"; // Nom de votre DB
$user = "root"; // Utilisateur de votre DB
$pass = "******"; // Mot de passe de la DB
try {
$db = new PDO('mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8', $user, $pass);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Ligne 4
}
catch(PDOException $e) {
    $msg = 'ERREUR PDO dans ' . $e->getFile() . ' L.' . $e->getLine() . ' : ' . $e->getMessage();
    die($msg);
}