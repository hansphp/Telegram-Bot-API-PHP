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
*/
//  //    		HTTP Client PHP Class
///////////////////////////////////////////////////////////////////
//  //       Autor : Hans Von Herrera Ortega
//  //     Versión : 1.0
//  //      Bugs a : hans.php@gmail.com
const CRLF = "\r\n";

class H_HTTP {
	protected $HOST;
	protected $PORT;
	protected $COOKIE = '';
	protected $DEBUG = false;
	protected $REQUEST_HEADERS; 
	protected $RESPONSE_HEADERS;
	protected $RESPONSE;
	protected $STATUS = false;
	
	function __construct($host, $port = 80)
	{
		$this->HOST = $host;
		$this->PORT = $port;
	}
		
	public function GET($path = '/', $headers = ''){
		return $this->socket('GET', $path, '', $headers);
	}
	
	public function POST($path = '/', $postdata = '', $headers = ''){
		if(gettype($postdata)=='array')
			$postdata = http_build_query($postdata);
				
		if(!empty($postdata))
			$headers.= 	'Content-type: application/x-www-form-urlencoded'	.CRLF.
						'Content-length: '.strlen($postdata) 				.CRLF;
		return $this->socket('POST', $path, $postdata, $headers);
	}
	
	public function RESPONSE(){
		return $this->RESPONSE;
	}
	
	public function setCookie($cookie)
	{
		$this->COOKIE = cookie;
	}
	
	public function __toString()
    {
        return __HVE;
    }
	
	protected function debug()
	{	
			 echo 	"<pre style=\"background:#CCC;border:solid 2px #000;padding:10px\">".$this->REQUEST_HEADERS.CRLF.
			 		"----------------------------".CRLF.
			 		$this->RESPONSE_HEADERS."</pre>";
	}
		
	public function debugOn()
	{
		$this->DEBUG = true;
	}
		
	public function debugOff()
	{
		$this->DEBUG = false;
	}
	
	protected function socket($method, $path = '/', $postdata = '', $headers = '')
	{
		$this->RESPONSE = '';
		$this->RESPONSE_HEADERS = '';
		$scheme = ($this->PORT==443)?'ssl://':'';
		$fp = fsockopen($scheme.$this->HOST, $this->PORT, $errno, $errstr, 10);
		if(!empty($fp)){
			$this->REQUEST_HEADERS	=	"$method $path HTTP/1.1".CRLF.
						'Host: '.$this->HOST.CRLF;
			$this->REQUEST_HEADERS.=		"User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; es-MX; rv:1.9.2.28) https://github.com/hansphp/Telegram-Bot-API-PHP".CRLF;
			$this->REQUEST_HEADERS.=		"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8".CRLF;
			
			/* Verify cookies */
			if(!empty($this->COOKIE))
				$this->REQUEST_HEADERS.= 'Cookie: '.$this->COOKIE.CRLF;
			/* Insert headers */
			if(!empty($headers)) $this->REQUEST_HEADERS.= $headers;
				$this->REQUEST_HEADERS.=	'Connection: close'.CRLF.CRLF;
			/* Verify postdata */
			if(!empty($postdata))
				 $this->REQUEST_HEADERS .= $postdata;
			
			fputs($fp, $this->REQUEST_HEADERS);
			/* Gets data */
			while (!feof($fp)){
				if(empty($this->RESPONSE_HEADERS) && strpos($this->RESPONSE, CRLF.CRLF)){
					$this->RESPONSE_HEADERS = trim($this->RESPONSE);
					$this->RESPONSE = '';
					$temp = explode(CRLF, $this->RESPONSE_HEADERS, 2);
					$temp = explode(' ', $temp[0]);
					$this->STATUS = (int) $temp[1];
					unset($temp);
				}
					$this->RESPONSE .= fgets($fp, 128);
			}
			
			fclose($fp);
			
			$this->chunked();
					
			if($this->DEBUG)
				$this->debug();
				
			return $this->STATUS;
		}else
			return false;
	}
	
	protected function chunked()
	{
		if (strpos($this->RESPONSE_HEADERS, 'Transfer-Encoding: chunked')){
			$add = strlen(CRLF); 
			$tmp = $this->RESPONSE; 
			$this->RESPONSE = ''; 
			do{ 
				$tmp = ltrim($tmp); 
				$pos = strpos($tmp, CRLF); 
				if ($pos === false) { return false; } 
				$len = hexdec(substr($tmp,0,$pos)); 
				if (!is_numeric($len) or $len < 0) { return false; } 
				$this->RESPONSE .= substr($tmp, ($pos + $add), $len); 
				$tmp  = substr($tmp, ($len + $pos + $add)); 
				$check = trim($tmp); 
				} while(!empty($check)); 
			unset($tmp);
		}
    }
}