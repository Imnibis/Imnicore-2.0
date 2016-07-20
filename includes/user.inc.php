<?php
if(LOGGED_IN) {
$select = $db->prepare('SELECT * FROM users WHERE id = ?');
$select->execute($_SESSION['id']);
$user = $select->fetch(PDO::FETCH_OBJ);
} else {
$user = '';
}