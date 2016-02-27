<?php defined('__HVE') or die('<tt>HansVon Engine</tt>'); // Security include
ini_set('display_errors', TRUE);
/* ///////////Developed By
  _   _       ___   __   _   _____        _     _   _____   __   _  
 | | | |     /   | |  \ | | /  ___/      | |   / / /  _  \ |  \ | | 
 | |_| |    / /| | |   \| | | |___       | |  / /  | | | | |   \| | 
 |  _  |   / / | | | |\   | \___  \      | | / /   | | | | | |\   | 
 | | | |  / /  | | | | \  |  ___| |      | |/ /    | |_| | | | \  | 
 |_| |_| /_/   |_| |_|  \_| /_____/      |___/     \_____/ |_|  \_| 
*/
//  //     Uploader de archivos
///////////////////////////////////////////////////////////////////
//  //      Author : Hans Von Herrera Ortega
//  //     Version : 0.1 BETA
//  //     Bugs to : ingeniero.php@gmail.com
//	//	  Requiere : PHP 5.3+ 
/*	Additionals:
Are necessary the extensions
fileinfo : http://www.php.net/manual/en/fileinfo.installation.php
*/
class H_FILE_UPLOAD extends H_HTTP{
	
	public function upload($path, $files, $postdata = array(), $headers = ''){
				
		$data='';
   		$boundary = __HVE.'Boundary'.sha1(__HVE);
		
		/* Datos por POST */
		foreach($postdata as $var => $d){
		$data .= '--'.$boundary.CRLF
				.'Content-Disposition: form-data; name="'.$var.'"'.CRLF.CRLF
				.$d.CRLF;
		}
		
		/* Archivos */
		foreach($files as $var => $file){
			$finfo = new finfo(FILEINFO_MIME);
			$mimetype = $finfo->file($file);
			// $file_contents = quoted_printable_encode(file_get_contents($file));
			$file_contents = file_get_contents($file);
			$data .= '--'.$boundary.CRLF
				.'Content-Disposition: form-data; name="'.$var.'"; filename="'.basename($file).'"'.CRLF
				.'Content-Type: '.$mimetype.CRLF
				.'Content-Length: '.strlen($file_contents).CRLF
				.'Content-Type: application/octet-stream'.CRLF.CRLF
				.$file_contents.CRLF;
		}
		
		$data .= '--'.$boundary.'--';
		
		$headers = "Content-type: multipart/form-data; boundary=".$boundary."\r\n"
		."Content-Length: ".strlen($data)."\r\n";
		return $this->socket('POST', $path, $data, $headers);
	}
}