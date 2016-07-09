<?php
define('START_TIME', microtime(true));
$DISPLAY_ERRORS = true;


##
#
# GESTION DES ERREURS
#
##

ini_set('display_errors', 'Off');
ini_set('html_errors', 0);
error_reporting(-1);

function ShutdownHandler() {
    if(@is_array($error = @error_get_last())) {
        return(@call_user_func_array('ErrorHandler', $error));
    };

    return(TRUE);
};

register_shutdown_function('ShutdownHandler');

function ErrorHandler($type, $message, $file, $line ) {
 global $DISPLAY_ERRORS;
    $_ERRORS = Array(
        0x0001 => 'E_ERROR',
        0x0002 => 'E_WARNING',
        0x0004 => 'E_PARSE',
        0x0008 => 'E_NOTICE',
        0x0010 => 'E_CORE_ERROR',
        0x0020 => 'E_CORE_WARNING',
        0x0040 => 'E_COMPILE_ERROR',
        0x0080 => 'E_COMPILE_WARNING',
        0x0100 => 'E_USER_ERROR',
        0x0200 => 'E_USER_WARNING',
        0x0400 => 'E_USER_NOTICE',
        0x0800 => 'E_STRICT',
        0x1000 => 'E_RECOVERABLE_ERROR',
        0x2000 => 'E_DEPRECATED',
        0x4000 => 'E_USER_DEPRECATED'
    );

    if(!@is_string($name = @array_search($type, @array_flip($_ERRORS))))
    {
        $name = 'E_UNKNOWN';
    };
 if ($DISPLAY_ERRORS) {
  printf("<table style=\"position: fixed;bottom: 0;right: 0;z-index:42696666942;\" dir='ltr' border='1' cellspacing='0' cellpadding='1'><tbody><tr><th align='left' bgcolor='#f57900' colspan='6'><span style='background-color: #cc0000; color: #fce94f; font-size: x-large;'>( ! )</span>".$message."</th></tr><tr><th align='left' bgcolor='#e9b96e' colspan='6'>Infos :</th></tr><tr><th align='center' bgcolor='#eeeeec'>Type</th><th align='center' bgcolor='#eeeeec'>Fichier</th><th align='center' bgcolor='#eeeeec'>Ligne</th><th align='center' bgcolor='#eeeeec'>Exec_time</th><th align='center' bgcolor='#eeeeec'>Ram</th></tr><tr><td bgcolor='#eeeeec' align='center'>".$name."</td><td bgcolor='#eeeeec' align='center'>".@basename($file)."</td><td bgcolor='#eeeeec' align='center'>".$line."</td><td bgcolor='#eeeeec' align='center'>".round((microtime(true)-START_TIME)*1000, 3)."ms</td><td bgcolor='#eeeeec' align='center'>".memory_get_usage()." o</td><br/>");
  return;
 }
};

$old_error_handler = set_error_handler("ErrorHandler");