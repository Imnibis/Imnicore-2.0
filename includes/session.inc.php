<?php
session_start();
if(!empty($_SESSION)) {
	define(LOGGED_IN, true);
} else {
	define(LOGGED_IN, false);
}