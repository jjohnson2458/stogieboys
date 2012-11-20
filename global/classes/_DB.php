<?php

class _DB 
{
	function __construct($class_name = "")
	{
		$args = func_get_args();
		if ($args[0] != "") {
			$this->where_field = $args[0]; 
		}

		if($args[1] != ""){
			$this->where_operator = $args[1];
		}

		if($args[2] != ""){
			$this->where_value = $args[2];
		}
		$this->table = preg_replace("/^\_\_/", '', strtolower(get_class($this)));
		$this->table = preg_replace("/[^a-z0-9\_]/", '', $this->table); // saftey feature in case someone is being sneaky
		
		$this->db = new MySQL(DB_NAME, DB_HOST, DB_USER, DB_PASS);
		$sql = "DESCRIBE `{$this->table}` ";
		$this->table_info = $this->db->GetAllRows($sql);		
		$this->db->GetFields($this->table);
		$this->get_row($this->where_field, $this->where_operator, $this->where_value);
		
	}

	function __toString()
	{
		return $this;
	}

	function get_primary_key()
	{
		$this->primary_key = $this->get_table_data('Key', 'PRI', 'Field');
		return ($this->primary_key);

	}

	function get_auto_increment_key()
	{
		$this->auto_increment_key = $this->get_table_data('Extra', 'auto_increment', 'Field');
		return ($this->auto_increment_key);		
	}

	function get_unique_key()
	{
		$this->unique_key = $this->get_table_data('Key', 'unique', 'Field');
		return ($this->unique_key);			
	}

	function get_field_type($field = "")
	{
		foreach((array)$this->table_info as $field_info){
			if($field == $field_info['Field']){
				return $field_info['Type'];
			}
		}
	}

	function get_table_data($key, $value, $return_field)
	{
		foreach($this->table_info as $field_info){			
			if($field_info[$key] == $value){				
				return $field_info[$return_field];
			}
		}
	
	}

	function get_row(){
		$args 		= func_get_args();		
		$field 		= (!empty($args[0]))  ? $args[0] : $this->where_field ;
		$operator 	= (!empty($args[1]))  ? $args[1] : $this->where_operator ;		
		$value 		= (!empty($args[2]))  ? $args[2] : $this->where_value ;	
		
		
		$sql = "SELECT * FROM `{$this->table}` ";
		$wheres = array(1);
		if($field != "" && empty($operator)  && empty($value)){
			$wheres[] = $this->get_primary_key() . ' = ' . Quote::add($this->db->Sanitize($field));
		} elseif($field != "" && !empty($operator) && empty($value)) {
			$status = 1;
			if(self::is_field($field)){
				$wheres[] = '`'. $field . '`' . ' = ' . Quote::add($this->db->Sanitize($operator));
			}
						
		} elseif($field != "" && !empty($operator) && !empty($value)) {
			if(self::is_field($field) && is_operator($operator) ){
				$wheres[] = '`'. $field . '` ' . $operator . ' ' . Quote::add($this->db->Sanitize($operator));
			}			
		} elseif($field == "") {
			return;
		}

		$this->default_sql = $sql . ' WHERE ' . AR::join($wheres, ' AND ');
		$this->row = $this->db->FetchRow($this->default_sql);
		$this->records = count($this->row);
		if(empty($this->row)){
			$this->_status = false;
		} else {
			$this->_status = true;
			foreach((array)$this->row as $field => $value){
				$this->{$field} = $value;
			}
			return  $this->row ; 	
		}
		

	}
	
	function get_status(){
		return $this->_status;	
	}
	
	function is_field($field = ""){
		return (in_array($field, $this->db->fields)	) ? true : false;
	}

	function __call($method = "", $values)
	{
		
	}

	function insert()
	{
		
	}

	function replace()
	{
		
	}

	/**
	* updates the current record object
	*
	*
	*/
	function update($inject = true)
	{

	}

	function delete()
	{
		
	}

	public function show($values = false)
	{
		
	}
}