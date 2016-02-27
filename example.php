<?php
date_default_timezone_set('America/Mexico_City');
define('__HVE','HansVonEngine');

require_once('assets/http.class.php');
require_once('assets/file.upload.class.php');
require_once('telegram.class.php');

/* Constante de configuración 
define ('TELEGRAM_TOKEN', '123456:ABC-DEF1234EJEMPLO');
Refierase a: https://core.telegram.org/bots/api#authorizing-your-bot */
require_once('config.php');


$BOT = new H_TELEGRAM_BOT(TELEGRAM_TOKEN);

$result = $BOT->getMe();

print_r($result);