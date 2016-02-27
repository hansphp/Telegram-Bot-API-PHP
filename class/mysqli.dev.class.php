<?php
defined("__HVE") or die("<tt>HansVon Engine</tt>");
ini_set('display_errors', TRUE);
/* ///////////Developed By
  _   _       ___   __   _   _____        _     _   _____   __   _  
 | | | |     /   | |  \ | | /  ___/      | |   / / /  _  \ |  \ | | 
 | |_| |    / /| | |   \| | | |___       | |  / /  | | | | |   \| | 
 |  _  |   / / | | | |\   | \___  \      | | / /   | | | | | |\   | 
 | | | |  / /  | | | | \  |  ___| |      | |/ /    | |_| | | | \  | 
 |_| |_| /_/   |_| |_|  \_| /_____/      |___/     \_____/ |_|  \_| 
 
     Manejador de Base de Datos para MySQL (Edición para desarrollo).

		Autor : Hans Von Herrera Ortega
		Versión : 2.0 Dev.
		Bugs a : hans.php@gmail.com
		URL: https://github.com/hansphp/misClases/blob/master/trunk/PHP/MySQL/mysqli.dev.class.php

The MIT License (MIT)

Copyright (c) 2014 Hans Von Herrera Ortega

Permission is hereby granted, free of charge, to any person obtaining a copy of
this software and associated documentation files (the "Software"), to deal in
the Software without restriction, including without limitation the rights to
use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
the Software, and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

class H_MYSQLi extends mysqli{
	private $conectado = false; # De entrada no debería de estar conectado.
	public $resultado;
	private $respuesta;
	public $with_htmlentities = true;
	private $sql;
	public $depurador = false;
	public $Merr=true; # Mostrar error 
	public static $instancias;
		
	function __construct() {
		if(!H_MYSQLi::$instancias++){ 
			@parent::__construct(H_MYSQL_HOST, H_MYSQL_USUARIO, H_MYSQL_CLAVE, H_MYSQL_BD);
			if (!$this->connect_error){
				$this->query("SET NAMES 'utf8'");# Charset UTF-8 
				$this->conectado = true;
			}else
				$this->errores($this->connect_error, $this->connect_errno);
		}else
			die("Hay mas de una instancia del objeto <b>'H_MYSQL'</b>.");
	}
	function __destruct() {
		if($this->conectado)
			$this->close();
	}
	public function conectado(){
		return $this->conectado;
	}
	public function fila($sql,$depurar=0){
		$this->sql=$sql;
		return $this->iconsulta('fila', $depurar);
	}
	public function consulta($sql,$depurar=0){
		$this->sql=$sql;
		return $this->iconsulta('consulta', $depurar);
	}
	public function update($sql,$depurar=0){
		$this->sql=$sql;
		return $this->iconsulta('update', $depurar);
	}
	public function insert($sql,$depurar=0){
		$this->sql=$sql;
		return$this->iconsulta('insert', $depurar);
	}
	public function delete($sql,$depurar=0){
		$this->sql=$sql;
		return $this->iconsulta('delete', $depurar);
	}
	private function iconsulta($tipo, $depurar){
		if($this->conectado && $this->sql){
			$this->resultado = false;
			
			if($this->resultado = $this->query($this->sql)){
				if($tipo=='fila'){
					$this->respuesta[0] = $this->resultado->fetch_object();
				}else if($tipo=='consulta'){
					$i=0;
					$this->respuesta = array();
					while($this->respuesta[] = $this->resultado->fetch_object()){
						$i++;
						if($i > 1000) 
							die("<b>FATAL:</b>  Su consulta genera mas de mil registros.");
					}
					array_pop($this->respuesta);
				}

				if($depurar || $this->depurador) $this->depurar();
				
				if($tipo=='fila')
					return $this->respuesta[0];
				else if($tipo=='consulta')
					return $this->respuesta;
				else if($tipo=='insert')
					return ($this->insert_id)?$this->insert_id:$this->affected_rows;
				else if($tipo==('update' || 'delete'))
					return $this->affected_rows;
			}else{
				@$this->errores($this->error, $this->errno);
			}
		}else{
			@$this->errores($this->error, $this->errno);
		}
	}
	private function depurar(){
		# No ejecutar donde $this->conectado y $this->resultado no sean válidos.
		$datos = array(
		16 => 'BIT',
		1 => 'TINYINT',
		2 => 'SMALLINT',
		9 => 'MEDIUMINT',
		3 => 'INT',
		8 => 'BIGINT',
		4 => 'FLOAT',
		5 => 'DOUBLE',
		246 => 'DECIMAL',
		10 => 'DATE',
		12 => 'DATETIME',
		7 => 'TIMESTAMP',
		11 => 'TIME',
		13 => 'YEAR',
		254 => 'CHAR',
		253 => 'VARCHAR',
		252 => 'BLOB');

		$col = @$this->resultado->field_count;
		echo "<table width=100% align=center border=1 rules=all style='background:#CCC;min-width:500px;font:normal 12px Arial,Verdana,Pursia'><tr><td colspan=$col style='padding:20px 0 0 20px' >
  <h2 style=background:#FFF><pre>".$this->colorear($this->sql)."</pre></h2>
  ".(($col)?"Columnas totales : $col<br>":"")."
  Filas afectadas : ".$this->affected_rows."
</td></tr>";
				if($this->affected_rows && $col){
					echo "<tr bgcolor=#000000>";
					foreach($this->resultado->fetch_fields() as $k=>$v){
						echo "<th align='center' style='color:#".($v->flags & MYSQLI_PRI_KEY_FLAG?"FF0":"FFF")."'>$v->name <font size='-1' color='#999999'>".(isset($datos[$v->type])?$datos[$v->type]:'')."(<label style='color:#C00'>$v->length</label>)<br><small>".($v->flags & MYSQLI_TYPE_NULL?"NOT NULL":"NULL")."</small></font></th>";
					}
				 	echo '</tr>';
				
					foreach($this->respuesta as $k => $v){
						echo "<tr bgcolor='#".(($k%2)?'FFFFFF':'CCCCCC')."'>";
						foreach($v as $x)
							echo "<td align='center'>".(($this->with_htmlentities)?htmlentities($x):$x)."</td>";
						echo "</tr>";
					}
				}
			echo ' </table>';
	}
	private function colorear($s){
		$a = '<br><label style="color:#06F">';
		$r = '<label style="color:#C00">';
		$f = '</label>';
		
		$se = array('SELECT', 'WHERE', 'FROM', 'INNER', 'JOIN', 'UPDATE', 'SET', '*');
		$re = array($a.$se[0].$f, $a.$se[1].$f, $a.$se[2].$f, $a.$se[3].$f, $a.$se[4].$f, $a.$se[5].$f, $a.$se[6].$f, $r.$se[7].$f);
		return str_ireplace($se, $re, $s);
	}
	private function errores($e, $n){
		if(!empty($this->Merr)){
			switch($n){
				case 2002:
				case 2005:
					$e="No se puede conectar al host: <b>'".H_MYSQL_HOST."'</b>";
				break;
				case 1045:
					$e="Acceso denegado a : <b>'".H_MYSQL_USUARIO."'@'".H_MYSQL_HOST."'</b> (USO CLAVE ".((H_MYSQL_CLAVE)?"SI":"NO").")";
				break;
				case 1064:
					$ex=explode("'",$e,2);
					$e="Se detecto un error de sintaxis en la consulta al usar : <font color='#CC0000'>'".str_replace("at line","</font> en la linea",$ex[1]);
				break;
				case 1049:
					$e="No se puede seleccionar la base de datos: <b>'".H_MYSQL_BD."'</b>";
				break;
				case 1146:
					$ex=explode("'",$e,2);
					$e="La tabla : <font color='#CC0000'>'".str_replace("doesn't exist","</font> no existe",$ex[1]);
				break;
			}
			if(!empty($n))
				echo("<div style='background:#FFF;color:#000'>$e Error no:$n</div>");
		}else{
			return false;
		}
	}
}