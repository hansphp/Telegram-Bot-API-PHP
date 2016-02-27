<?php
defined('__HVE') or die('<tt>HansVon Engine</tt>');
ini_set('display_errors', TRUE);
/* ///////////Developed By
  _   _       ___   __   _   _____        _     _   _____   __   _  
 | | | |     /   | |  \ | | /  ___/      | |   / / /  _  \ |  \ | | 
 | |_| |    / /| | |   \| | | |___       | |  / /  | | | | |   \| | 
 |  _  |   / / | | | |\   | \___  \      | | / /   | | | | | |\   | 
 | | | |  / /  | | | | \  |  ___| |      | |/ /    | |_| | | | \  | 
 |_| |_| /_/   |_| |_|  \_| /_____/      |___/     \_____/ |_|  \_| 

The MIT License (MIT)

Copyright (c) 2016 Hans Von

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

*/
//  //    		Telegram-Bot-API-PHP Class
///////////////////////////////////////////////////////////////////
//           Autor : Hans Von Herrera Ortega
//         Version : 1.0
//	  	  Requiere : PHP 5.3+ 
//            Bugs : hans.php@gmail.com
//          GitHub : https://github.com/hansphp/Telegram-Bot-API-PHP
//	  Designed for : https://core.telegram.org/api

class H_TELEGRAM_BOT extends H_FILE_UPLOAD{
	
	protected $token;
	
	function __construct($token)
	{
		$this->setSocket();
		$this->token = $token;
	}
	
	protected function setSocket(){
		parent::__construct('api.telegram.org', 443);
	}
	
	public function GET($method = 'getMe', $headers = ''){ 
		parent::GET("/bot{$this->token}/{$method}", $headers);
		
		return json_decode($this->RESPONSE);
	}
	
	public function POST($method = '', $postdata = '', $headers = ''){
		parent::POST("/bot{$this->token}/{$method}", $postdata, $headers);
		
		return json_decode($this->RESPONSE);
	}
	
	/* Metodos de Telegram */
	
	/**
	  * A simple method for testing your bot's auth token. Requires no parameters. Returns basic information about the bot in form of a User object.
	  *
	  * @since 1.0
	  *
	  * @return User Object
	  */
	public function getMe(){
		return $this->GET('getMe');
	}
	
	/**
	  * Use this method to receive incoming updates using long polling (wiki).
	  * https://en.wikipedia.org/wiki/Push_technology#Long_polling
	  *
	  * @since 1.0
	  *
	  * @param offset $description Identifier of the first update to be returned. Optional.
	  * @param limit $description Limits the number of updates to be retrieved. Optional.
	  * @param timeout $description Timeout in seconds for long polling. Optional.
	  *
	  * @return Array of Update objects
	  */
	public function getUpdates($offset = 0, $limit = 100, $timeout = 0){
		$data = array();
		
		if($offset)		$data['offset'] = $offset;
		if($limit != 100)	$data['limit'] = $limit;
		if($timeout)	$data['timeout'] = $timeout;
		
		return $this->POST('getUpdates', $data);
	}
	
	/**
	  * Use this method to send text messages. On success, the sent Message is returned.
	  *
	  * @since 1.0
	  *
	  * @param chat_id $description Unique identifier for the target chat or username of the target channel (in the format @channelusername). Required.
	  * @param text $description Text of the message to be sent. Required.
	  * @param parse_mode $description Send Markdown or HTML, if you want Telegram apps to show bold, italic, fixed-width text or inline URLs in your bot's message. Optional.
	  * @param disable_web_page_preview $descriptionDisables link previews for links in this message. Optional.
	  * @param disable_notification $description Sends the message silently. Optional.
	  * @param reply_to_message_id $description If the message is a reply, ID of the original message. Optional.
	  * @param reply_markup $description Additional interface options. Optional. --- TODO : Agregar esta funcionalidad.
	  *
	  * @return Message object
	  */
	public function sendMessage($chat_id, $text, $parse_mode = '', $disable_web_page_preview = false, $disable_notification = false, $reply_to_message_id = 0){
		$array = array('chat_id' => $chat_id, 'text' => $text);
		
		if(strlen($parse_mode) > 0) $array['parse_mode'] = $parse_mode;
		if($disable_web_page_preview) $array['disable_web_page_preview'] = 'true';
		if($disable_notification) $array['disable_notification'] = 'true';
		if($reply_to_message_id) $array['reply_to_message_id'] = $reply_to_message_id;
		
		return $this->POST('sendMessage', $array);
	}
	
	/**
	  * Use this method to forward messages of any kind. On success, the sent Message is returned.
	  *
	  * @since 1.0
	  *
	  * @param chat_id $description Unique identifier for the target chat or username of the target channel (in the format @channelusername). Required.
	  * @param from_chat_id $description Unique identifier for the chat where the original message was sent (or channel username in the format @channelusername). Required.
	  * @param disable_notification $description Sends the message silently. Optional.
	  * @param message_id $description Unique message identifier. Optional.
	  *
	  * @return Message object
	  */
	public function forwardMessage($chat_id, $from_chat_id, $message_id){
		return $this->POST('forwardMessage', array('chat_id' => $chat_id, 'from_chat_id' => $from_chat_id, 'message_id' => $message_id));
	}
}