<?php

class CSV 
{

	CONST self=__CLASS__; 
	
	function load($filename,$header=true,$separator=","){
		if (!file_exists($filename)) {
			return array();
		}
		$fh=fopen($filename,'r');
			while (($data = fgetcsv($fh, 1024, $separator)) !== FALSE) {
				if ($header){
					foreach ($data as $key=>$field){
						$fields[]=$field;
					} 
				$header=false;
				$have_fields=true;
				} else {
					$have_fields = true;
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
  
  /* 
   *
   */
   function  addQuote($array)
   {
		foreach ((array)$array as $key=>$value){
			$returnArray[$key]= chr(39).trim(str_replace(chr(39),chr(92).chr(39),$value)).chr(39);
		}
		return $returnArray;
   }
  
  /* 
   *
   */
   function  addQuotes($array)
   {
		return array_map(array('CSV','_addQuotes'),$array);
   }

    
  /* 
   *
   */
   function  join($array)
   {
		return AR::join($array,CR);
   }

  /* 
   *
   */
   function  _addQuotes($text)
   {
		return chr(34).trim(str_replace(chr(34),chr(92).chr(34),$text)).chr(34);
   }

    
     
  /* 
   *
   */
   function  build($array)
   {
		$csv=array();
		foreach ($array as $line=>$row){
			$csv[]=AR::join($row);
		}
		return self::join($csv);
   }
  
  /* 
   *
   */
   function  write($array, $filename = "")
   {
		if ($filename!=""){
			$csv=self::build($array);
			File::write($csv,$filename);
		}
   }

  
  /* 
   *
   */
function save($array,$header=array(),$makeHeader=false){ 
	
	global $cm;
	global $cr;
	$a=0;
		if ($makeHeader)$lines[]=join($cm,array_map(array('CSV','_addQuotes'),$header));
		foreach($array as $line=>$row){
			$lines[]=join($cm,array_map(array('CSV','_addQuotes'),$row));
			$a++;
		}	
	$csv=join($cr,$lines);
	return $csv;	
}
   
   
   function  __destruct()
   {

   }
}
