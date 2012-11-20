<?php

class AR
{
	CONST self=__CLASS__;
	
	private function AR(){
		return ;
	}
	
	public static function set($array=array()){
		return (array)$array;
	}
	
	public static function push($value="",$array){
		if (Html::isString($value)) return array_push(self::set($array),$value);
	}
	
	public static function pop($array){
		$dump=array_pop(self::set($array));
		return $array;
	}
	
	/* replacement for in_array()*/
	public static function is_in($elem, $array){ 
   $top = sizeof($array) -1; 
   $bot = 0; 	
	   while($top >= $bot) { 
		  $p = floor(($top + $bot) / 2); 
		  if ($array[$p] < $elem) $bot = $p + 1; 
		  elseif ($array[$p] > $elem) $top = $p - 1; 
		  else return TRUE; 
	   }      
   	return FALSE; 
	} 
	
	/* returns shifted $array; removing first value */
	public static function shift($array){
		$dump=array_shift(self::set($array));
		return $array;		
	}
	
	/* remove $value from $array */
	public static function remove(array $array, $value = "", $strict = false){
		return array_diff_key($array, array_flip(array_keys($array, $value, $strict)));
	}

	/* remove value from $array as determined by $key*/
	public static function keyRemove($array,$key=""){
		if(array_key_exists($key, $array)){
			unset($array[$key]);
			return $array;	
		}
		return $array;
	}	
	
	/* return first item from numeric or associative array */
	public static function start($array){
		reset(set($array));
		return each($array);
	}
	
	/* =array_map
	*  @todo: make recursive
	 */
	public static function map($array,$callback,$recursive=false){
		if (is_array($array) && function_exists($callback)) return array_map($callback,$array);
	}

	/* looks for $value anywhere in $array*/
	public static function check($array=array(),$value=''){
		if (Html::isString($value)){
			return array_keys($array,$value);
		}
	}
	
	/**
*  assoc
* 	turns a numeric key indexed array into an associative array 
* @param array $array
* @return array
* @todo: add opiton to select overwring of 
*
*
*/
	public static function assoc($array){		
			foreach ((array)$array as $key=>$value){
				$outArray[$value]=$value;
			}
		return $outArray;
	}
	
	public static function string($array,$join=','){
		return join($join,self::set($array));
	}
	
	public static function join($array,$join=','){
		return join($join,self::set($array));
	}
		
	public static function hash($array,$join=','){
		return md5(self::string($array,$join));
	}
	
	/* return unique values of array */
	public static function unique($array){
		return array_unique(self::set($array));
	}
	
	/**
 * Get the array value of $array. If $array is null, it will return
 * the current array Set holds. If it is an object of type Set, it
 * will return its value. If it is another object, its object variables.
 * If it is anything else but an array, it will return an array whose first
 * element is $array.
 *
 * @param mixed $array Data from where to get the array.
 * @return array Array from $array.
 * @access private
 */
	public static function a($array) {
		if (empty($array)) {
			$array = array();
		} elseif (is_object($array)) {
			$array = get_object_vars($array);
		} elseif (!is_array($array)) {
			$array = array($array);
		}
		return $array;
	}
	
	/* returns the json string of the array */
	public static function asJson($array=array()){
		return JSON::encode(self::set($array));
	}
	
	/* returns the object of the array */
	public static function asObject($array=array()){
		
	}

	/* returns the js array of the array */
	public static function asJS($array=array()){
		
	}
	
	/* return array matches based on $pattern*/
	public static function matches($array,$pattern){
		
	}
	
	/*returns an array from a multi-dimensional array as determined by a sub key*/
	public static function extract($array,$key=""){
		#if(array_key_exists($key,self::set($array))){
		$newArray=array();
			foreach ($array as $k=>$value){
				$newArray[]=$value[$key];
			}
		return $newArray;
		#}
	}
	
  /* 
   * build and array 
   * adds a name/vaule pair to an array, or the appened a new value to the array as name=value
   * @param array $inArray;
   * @param array/string $name
   * @param array/string $value 
   * @return mixed
   */
   public static function  build($inArray,$name,$value="")
   {
		$outArray=$inArray;
		if (is_array($name)){
			foreach ((array)$name as $key=>$value){
				$outArray[$name]=$value;				
			}
		} else {
			$outArray[$name]=$value;		
		}
		return $outArray;
   }
   
   /* 
   *
   */
   public static function  bind($array)
   {
		$params=array();
		foreach ((array)$array as $key=>$value){
			$params[]="$key='$value'";		
		}
		return ' '.self::join($params,' ');
   }	
   
	  /**
		returns the average value of all array-values
		or false if no values in array (or not an array)
	  */     
   public static function avg(&$array){
	  if (!is_array($array) || count($array) == 0){ 
		return false;
	  } else {
		return array_sum($array) / count($array);
	  } // array_avg()
	}
	
	/**
	* applies array_map to an array using multiple system or user-defined functions
	*
	*/
	
	public static function multi_map($array = false, $functions = array()){
		 if($array){
			$array = self::a($array);
			foreach((array)$functions as $class => $method){
				if(class_exists($class) && $method != ""){
					$array = array_map(array($class, $method), $array);	
				} else {
					if(function_exists($method)){
						$array = array_map($method, $array);
					}
				}
			}	 
			return $array;
		 }
	}

	/**
	* sorts a multi-dimensional array for placing data in the correct columns
	*
	*/
	public static function column_sort(&$array, $column=2){
		$m = ceil(count($array)/$column);
		$j = 0;
		for($i = 0; $i < $m; $i++) {
			for($k = 0; $k < $column; $k++) {
				$key = $i + ($m * $k);
				settype($key,'integer');
				if(array_key_exists($key, $array)) {
					$b[$j] = $array[$key];
					$j++;
				}
			}
		}
		$array = $b;
	}

}






