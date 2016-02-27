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
	 * @since 1.0
	 *
	 * @return User
	 */
	public function getMe(){
		return $this->GET('getMe');
	}
	
	public function getUpdates($offset = 0){
		$data = array();
		
		if($offset > 0){
			$data['offset'] = $offset;
		}
		
		return $this->POST('getUpdates', $data);
	}
	
	public function sendMessage($chat_id, $text, $reply_to_message_id = 0){
		$array = array('chat_id' => $chat_id, 'text' => $text);
		
		if($reply_to_message_id){
			$array['reply_to_message_id'] = $reply_to_message_id;
		}
		
		return $this->POST('sendMessage', $array);
	}
}