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

define ('TEST_ID', uniqid());

echo '<h1>TEST: '.TEST_ID.'</h1>';

$BOT = new H_TELEGRAM_BOT(TELEGRAM_TOKEN);
$BOT->debugOn();

$result1 = $BOT->getMe();
print_r($result1);

$result2 = $BOT->getUpdates(0, 1);
echo '<pre>';
print_r($result2);
echo '</pre>';

$result3 = $BOT->sendMessage($result2->result[0]->message->chat->id, 'Hola mundo! <i>'.TEST_ID.'</i> visit: https://github.com/hansphp/Telegram-Bot-API-PHP', 'HTML', true, true, $result2->result[0]->message->message_id);
echo '<pre>';
print_r($result3);
echo '</pre>';


$result4 = $BOT->sendPhoto($result2->result[0]->message->chat->id, 'logoPHP.png', 'Lenguaje de programación');
echo '<pre>';
print_r($result4);
echo '</pre>';