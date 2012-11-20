<?php
// build off of this example
/*
$tb = new TableObj($table_params);
$tb->row_colors($light = 'ffffff', $dark = 'cccccc');

$tb->build($multiLevelArray);

//or

foreach($line as $row){		
	foreach($row as $key => $value){			
		$tb->add($value, $td_params);
	}
	$tb->highlight_row($value, $color);
	$tb->add_row($cells, $tr_params);
}

echo $tb;
*/

class TableObj
{
	CONST self = __CLASS__;
	
	var $max_cell_count;
	
	function __construct($table_params = false){
		$this->set_table_params(table_params);
		$this->max_cell_count = 0;
	}
	/**
	* set table parameters
	* @param array $table_params - will also accept a string or bind name/value pairs together
	* @return  string
	*/
	function set_table_params($table_params = false){
		if($table_params){
			if(is_array($table_params)){
				$this->table_params = AR::bind($table_params);
			} else {
				$this->table_params = $table_params;
			}
		}		
	}
	
	/**
	* will set a toggle flag calling in the table(when built) to flag off and on during row iteration
	* @param string $light - lighter color default 'ffffff'
	* @param string $dark - darker color - default 'cccccc'
	* 
	*/
	function set_colors($light = 'ffffff', $dark = 'cccccc'){
	
	}
	
	/**
	* sets the color of the row during mouse over
	* @param color - must be a valid 6-digit color
	*
	*/
	function set_mouseover_color($color = 'ff0000'){
		if(preg_match("/[a-fA-F0-9]/", $color)){
			$this->set_mouseover_color = $color;
		}
	}
	
	
	
	
	/**
	* sets the color of the row during mouse over
	* @param color - must be a valid 6-digit color
	*
	*/
	function add($cell = "", $td_params = false){	
		$this->td_params = (is_array($td_params))  ? AR::bind($td_params) : (($td_params)  ? $td_params : '' ) ;
		if(is_array($cell)){		
			foreach($cell as $key => $value){
				$this->tds[]  = Table::td($cell, $this->td_params);
			}
			self::get_max_cell_count();
		} else {
			$this->tds[]  = Table::td($cell, $this->td_params);
			self::get_max_cell_count();
		}
	}
	
	/**
	*
	*
	*
	*/
	function get_max_cell_count(){
		if(count($this->tds) >= $this->max_cell_count){
			$this->max_cell_count = count($this->tds);
		}
	}	
	
		
}