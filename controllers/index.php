<?php
$text = 'TEST REUSSI!';

// AFFICHAGE DU TEMPLATE
$template = $imnicore->getTemplate('index');
eval($template);