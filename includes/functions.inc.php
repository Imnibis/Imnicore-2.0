<?php

#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|
#|                                  #|
#|             IMNICORE             #|
#|                                  #|
#|           par Imnibis            #|
#|                                  #|
#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|#|

function redirect($url, $method = "javascript", $exit = false, $rank = false) {
	if($rank) {
		echo "<script>alert('ACCES INTERDIT!');</script>";
	}
	switch($method) {
		case "javascript":
		echo "<script>window.location.replace(\"" . $url . "\");</script>";
		break;
		case "HTTP":
		header('Location: ' . $url . '');
		break;
		default:
		$exception = 'Le type de redirection doit &ecirc;tre "javascript" ou "HTTP"';
		exception($exception);
		break;
	}
	if($exit) {
		exit;
	}
}
function exception($error) {
	$error = "ERREUR: " . $error . " ~~~ POWERED BY IMNICORE";
	die($error);
}
function allowed($type) {
	switch($type) {
		case "login":
		if(!LOGGED_IN) {
			redirect($settings->url, "javascript", true);
		}
		break;
		case "visitor":
		if(LOGGED_IN) {
			redirect($settings->url . "/me");
		}
		break;
		case "staff":
		if(!LOGGED_IN) {
			redirect($settings->url, "javascript", true, true);
		} elseif($user->rank < 7) {
			redirect($settings->url . "/me", "javascript", true, true);
		}
		break;
		default:
		$exception = "Le type de restriction doit &ecirc;tre \"login\", \"visitor\" ou \"staff\"";
		exception($exception);
	}
}

function secu($str) {
	$str = nl2br(htmlspecialchars(stripslashes(trim($str))));
	return $str;
}
function viewDate($date){
if($date == date('Y-m-d')) return 'Aujourd\'hui';
else if(strtotime($date) == strtotime(date('Y-m-d').' - 1 DAY')) return 'Hier';
else if(strtotime($date) == strtotime(date('Y-m-d').' - 2 DAY')) return 'Avant hier';
else return $date;
}
		
function FullDate($str) {
	$H = date('H');
	$i = date('i');
	$s = date('s');
	$m = date('m');
	$d = date('d');
	$Y = date('Y');
	$j = date('j');
	$n = date('n');
					
	switch ($str)
	{
	case "day":
	$str = $j;
	break;
	case "month":
	$str = $m;
	break;
	case "year":
	$str = $Y;
	break;
	case "today":
	$str = $d;
	break;
	case "full":
	$str = date('d-m-Y H:i:s',mktime($H,$i,$s,$m,$d,$Y));
	break;
	case "datehc":
	$str = "".$j."-".$n."-".$Y."";
	break;
	default:
	$str = date('d-m-Y',mktime($m,$d,$Y));
	break;
	}				
	return $str;
}

function TicketRefresh($username) {
	for($i = 1; $i <= 3; $i++):
	{
	$base = $base . rand(0,99);
	$base = uniqid($base);
	}
	endfor;
	$base = $settings->Nickname . "-" . $base . "-" . $settings->Nickname;
	$request = $db->prepare("UPDATE users SET auth_ticket = ? WHERE username = ? LIMIT 1");
	$request->execute(array($base, $username));
	return $base;
}

function ImnicoreHash($str)
{
$config_hash = "bfzepofezpzebfeziàoenfç_ébféifnébç_féféifbéç_boi";
$str = secu(sha1($str . $config_hash));
return $str;
}