<?php
class File
{
	CONST self=__CLASS__;
	
	function loadcsv($filename,$header=true){
		if (!self::exists($filename)) {
			return array();
		}
		$fh=fopen($filename,'r');
			while (($data = fgetcsv($fh, 1024, ",")) !== FALSE) {
				if ($header){
					foreach ($data as $key=>$field){
						$fields[]=$field;
					} 
				$header=false;
				$have_fields=true;
				} else {
					$i=0;
					foreach ($data as $key=>$value){
						($have_fields)  ?  $line[$fields[$i]]=$value  : $line[$i]=$value ;
						$i++;
					}
				$csv[]=$line;		
				}
			}
			fclose($fh);
			return $csv;		
	}
	
	function write($data="",$filename="",$append=false){	
		if ($filename!=""){
			$type=($append)  ? 'a+' : 'w+' ;
			$fh=fopen($filename,$type);
			if (fwrite($fh,$data)) { 
				fclose($fh);
				return true;
			} else {
				fclose($fh);
				return false;
			}
		}
	}
	
	function load($url){
		  $curl = curl_init();
		
		  // Setup headers - I used the same headers from Firefox version 2.0.0.6
		  // below was split up because php.net said the line was too long. :/
		  $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
		  $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
		  $header[] = "Cache-Control: max-age=0";
		  $header[] = "Connection: keep-alive";
		  $header[] = "Keep-Alive: 300";
		  $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
		  $header[] = "Accept-Language: en-us,en;q=0.5";
		  $header[] = "Pragma: "; // browsers keep this blank.
		
		  curl_setopt($curl, CURLOPT_URL, $url);
		  curl_setopt($curl, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)');
		  curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		  curl_setopt($curl, CURLOPT_REFERER, 'http://www.google.com');
		  curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate');
		  curl_setopt($curl, CURLOPT_AUTOREFERER, true);
		  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		  curl_setopt($curl, CURLOPT_TIMEOUT, 10);
		
		  $html = curl_exec($curl); // execute the curl command
		  curl_close($curl); // close the connection
		
		  return $html; // and finally, return $html		
	}
	
	function read($filename=""){
		if(is_file($filename) && self::exists($filename)) return file_get_contents($filename);
	}
		
	function get($filename=""){
		if(is_file($filename) && self::exists($filename)) return file_get_contents($filename);
	} 

	function scopy($source,$target){
		return self::write(self::load($source),$target);
	}
	
	function ini($filename="",$setGlobals=true){
		if(is_file($filename) && self::exists($filename)){
			$array=parse_ini_file($filename);
			if ($setGlobals){
				foreach ($array as $name=>$value){$GLOBALS[$name]=$value;}
				return;
			} else {
				return $array;
			}
		}
	}
	
	function append(){
	
	} 
	function exists($filename){
		return (is_file($filename) && file_exists($filename))  ? true : false ;
	}
	
	function delete(){
		if (self::exists($filename)) return (@unlink($filename))  ? true  : false ; 
	} 
	
	function accesstime($filename){
		if (self::exists($filename)) return @fileatime($filename);
	} 
	
	function owner($filename){
		if (self::exists($filename)) return @fileowner($filename);
	} 
	
	function group($filename){
		if (self::exists($filename)) {
			$array=posix_getgrgid(@filegroup($filename));
			return $array['name'];
		}
	} 	
	
	function modified($filename){
		if (self::exists($filename)) return @filemtime($filename);
	} 
	
	function created($filename){
		if (self::exists($filename)) return @filectime($filename);
	} 
		
	function getExt($filename){		
			$fileparts=explode('.',$filename);
			if(is_array($fileparts)) return strtolower(array_pop($fileparts));	
		
	} 
	
	function size($filename){
		if(self::exists($filename)) return @filesize($filename);
	}
	
	function serialize($array,$filename){
		return self::write(serialize($array),$filename);
	}
	
	function unserialize($array,$filename){
		return unserialize(self::get($filename));
	}
	
	function stat($filename){		
			if (self::exists($filename)){
				return @stat($filename);
			} else {
				return false;
			}		
	}
	
	function LoadAsArray($filename=""){
		if(self::exists($filename)){
			$array=file($filename);
			return array_map('trim',$array);
		}
	} 	
		
	function scan($dir,$path=true){
		if (is_dir($dir)){
			$folder=$dir.SLASH;
			$rawfiles=array_merge(array(),array_diff(scandir($dir),array('.','..')));			
			$files=array();
				foreach ($rawfiles as $file){
					if (is_file($folder.$file) && !is_dir($folder.$file)){
						$files[]=($path)  ? $folder.$file : $file ;
					}
				}				
			return $files;			
		}
	}
	
	function scanall($dir,$path=true){
		if (is_dir($dir)){
			$folder=$dir.SLASH;
			$rawfiles=array_merge(array(),array_diff(scandir($dir),array('.','..')));			
			$files=array();
				foreach ($rawfiles as $file){
					if (is_file($folder.$file) || is_dir($folder.$file)){
						$files[]=($path)  ? $folder.$file : $file ;
					}
				}				
			return $files;			
		}
	}

	function getdirs($dir,$path=true){
		if (is_dir($dir)){
			$folder=$dir.SLASH;
			$rawfiles=array_merge(array(),array_diff(scandir($dir),array('.','..')));			
			$files=array();
				foreach ($rawfiles as $file){
					if (is_dir($folder.$file)){
						$files[]=($path)  ? $folder.$file : $file ;
					}
				}				
			return $files;			
		}
	}
	
	function lock($filename){
	
	}
	
	function unlock($filename){
	
	}
}

