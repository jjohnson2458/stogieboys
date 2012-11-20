<?php

/**
* class SQL
* rebuild to work with zen cart queryFacotory Class
*
*/

class SQL extends MySQL 
{ 

	
	var	$Table=				""; 
	var $Fields=			array();
	var $WhereClause=		"";
	var $Data=				array();
	var $NoHash=			array();
	var $Wheres=			array();
	var $Limits=			"";
	var $Order=				"";
	var $dbFunctionFields=	array();

	var $wheres				= array();
	var $placholders		= array();
	const self =		__CLASS__;
	
	
	function SQL($table = "", $whereField = "", $equals = "")  
	{
		$this->Connect();
		if ($table=="") return false; 
		$this->Table=			self::Clean($table);		
		unset($this->Fields);
		$this->isLoaded=		false;
		$this->Error=			false;
		$this->Fields=			$this->GetFields($this->Table);		
		$this->WhereClause = 	" WHERE 1=2 ";
		$this->updLimit=		true;
		$this->result=			false;
		$this->selectVars=		'*';
		global					$cr;
	    global					$br;

		// split table string to check for aliases
		$alias = explode(' ', $this->Table);
		if(count($alias) > 1){
			$_table = trim($alias[0]);
			$_alias = trim($alias[1]);
			$this->fields[$_table] = $_alias;
			$this->fields[$_table] = $this->GetFields[$_table];
		}
		
		
		/* connect to data base. this class assumes a db connection of $db */
		if ($whereField!="" && $equals!="") {
			$this->Where($whereField,$equals);
		}
		

	}
	
	function isField($field)
	{
		$field_parts = explode('.', $field);
		if(count($field_parts) > 1){
			return ( array_key_exists($field_parts[0]) && in_array($field_parts, $this->fields[$field_parts[0]]) ) ? true : false ;
		} else {
			return (in_array($field, $this->Fields))  ? true : false ;
		}
		
	}
	
	function removeByKey($array,$key,$resetKeys=false){
		$holding=array();
		$newKey=0;
		foreach($array as $key => $v){
			if($k!=$key){
				if ($resetKeys){
					$holding[$newKey]=$v;
					$newKey++;
				} else {
					$holding[$k]=$v;
				}
			}
		}   
		return $holding;
	}	
	
	function __call($field,$value)
	{
		if (self::isField($field))$this->Data[$field]=join('',$value);		
	}
	
  	function Set($name="",$value="")
	{
		if (is_array($name)){
			$this->Data=array_merge($this->Data,$name);
		} else if ($name!=""){
			$this->Data[$name]=$value;
		}
	}
	
	/*================================== BUILD QUERY SECTION ====================================*/

	//function Where($field="",$value="",$operator='LIKE',$and=false)
	function Where($whereclause = "", $screen = true){
		
		if($whereclause !=""){
			if($screen){
			$where_parts = preg_split("/(\=|\<|\>|LIKE|IN|like|in|AND|and|or|OR|\+|\-\<=\>=])/i", $whereclause, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
			//PR::r($where_parts);
			foreach((array)$where_parts as $index => $string){
				//PR::red($string);
				// this is a field - make sure it exists
				if ( (self::isField(str_replace('`', '', $string)) || ctype_digit($string)) || (preg_match("/`(.*)`/", $string) || $index == 0 )){
					$safe_parts[] = '`' . trim($string) . '`';
					PR::l($string. ' is table field');					
				} elseif(preg_match("/^\'/", trim($string)) && preg_match("/\'$/", trim($string))){
					// its a variable - give it a place holder
					$safe_parts[] = '?';
					$this->placeholders[] = $this->Slash( preg_replace("/^\'(.+?)\'$/", '$1', trim($string)) ); //	
						PR::l($string. ' is variable');
				} else {
					// its neither
					$safe_parts[] = '?';
					$this->placeholders[] = $string;
					PR::l($string . ' is neither');
				}				
			} // end foreach
				$this->wheres[] = join('', $safe_parts);
		} else {
			$this->wheres[] = $whereclause;
		}
				//PR::r($this->wheres);
				//PR::r($this->placeholders);

		}

		
		if ($value!="") {
			if (self::isField($field)){				
				$this->Wheres[]=" `".$this->Clean($field)."` $operator '".$this->Clean($value)."' ";
			}
		 } else {
		 	$this->Wheres[]=" $field ";			
		 }
		
		$join=($and)  ? ' AND ' : ' OR ' ; 
		if (count($this->Wheres) > 0) { 
			$this->WhereClause =  ' WHERE '.join($join,$this->Wheres);			
		} else {
			$this->WhereClause = " ";
		}
	}
	
	function Limit($start="",$limit="")
	{
		if (is_numeric($start) && $start!="")$this->limits[]=" $start ";
		if (is_numeric($limit) && $limit!="") $this->limits[]=" $limit ";
		$this->sqlLimit=join(',',$this->limits);
	}
	
	function OrderBy($orderBy="",$direction='asc')
	{
		if (self::isField($orderBy)){
			$this->orderBys[$orderBy]=(trim($direction)!='asc')  ? ' DESC ' : ' ASC ' ;
			if (count($this->orderBys) > 0){
				$orderParts=array();
				foreach ($this->orderBys as $order=>$dir){
					$orderParts[]="`{$order}` {$dir}";
				}
			$this->Order=" ORDER BY ".join(',',$orderParts);
			}
		}
	}
	
	function Group($groupBy="",$having="")
	{
		
	}
	
	function LeftJoin($joinTable="",$joinTableField="",$tablefield="")
	{
		/* ex: $sql->LeftJoin(userinfo,memberId,userId);
			   $sql->LeftJoin($joinTable,$joinTableField,$tablefield);
		*/
	}
			
	function RightJoin($joinTable="",$joinTableField="",$tablefield="")
	{
		
	}

	/*
	* Selects which field to NOT include when hashing
	*/
	function SetNoHash($field=""){
		if (self::isField($field)) $this->NoHash[]=$field;
	}	
	/*
	* Selects which field to receive hash result
	*/			
	function SetChecksum($field='checksum')
	{
		if (self::isField($field)){
			$this->NoHash[]=$field;  // add this field to the list NOT to be included when hashing
			$this->checksumField=$field;
		}
	}
	
	function Checksum()
	{
		$this->HashArray=array_diff($this->Data,$this->NoHash);
		$this->Checksum=md5(join(',',$this->HashArray));
		$this->Data['checksum']=$this->Checksum;
	}	
	
	function dbFunction($field="",$value="")
	{
		/*used to apply a db function to a field */
		/*example $this->dbFunction('datetime','NOW()'); */
		if (self::isField($field)) {
			$this->dbFunctionFields[$field]=$value;
			$this->Data[$field]=$value;
		}
	}
	
	function arrayToString($array,$glue=','){
		$strings=array();
		foreach ((array)$array as $key=>$value) {
			if (is_array($value)) {
				$strings[]=self::arrayToString($value,$glue);	
			} else {
				$strings[]=$value;
			}
		}
		return join($glue,(array)$strings);
	}
	
	/*note: when parsing array for select statement array key is field, value is alias (if any)*/
	function ParseForLoad()
	{
		if (is_array($this->Data) && count($this->Data) > 0){
			$this->Data=$this->Clean($this->Data);
			$selectFields=array();
			foreach($this->Data as $field=>$alias){			
				if (self::isField($alias)) {
					$selectFields[]="`{$this->Table}`.`{$alias}`";
				} else if(self::isField($field)){
					$selectFields[]="`{$this->Table}`.`{$field}`";
				}
			}
				foreach ((array) $this->Aliases as $field=>$alias){
					if (self::isField($field)){
						$selectFields=self::removeByKey($selectFields,$field);
						$selectFields[]="`{$this->Table}`.`$field` as `".$this->Clean($alias)."`";
					}
				}
			$this->selectVars=join(',',$selectFields);
		} else {
			$this->selectVars="`{$this->Table}`.* " ;
		}
	}
	
	function saveArray($array) // sets $this->Data to input $array 
	{
		if (is_array($array))$this->Data=$array;
	}
	
	function Clean($arrayOrField)
	{
        if (!is_array($arrayOrField)) return $this->Slash($arrayOrField);
			foreach ((array) $arrayOrField as $field=>$value){
				$name=$this->Slash($field);
				$cleanedArray[$name]=$this->Slash($value);
			}
		return $cleanedArray;
					
	}	
	
	function Slash($text="")
	{
		$magicQuotes=(get_magic_quotes_gpc())  ? true : false ;
		$data=($magicQuotes)  ? stripslashes($text) : $text ;
		return mysql_real_escape_string($data,$this->conn);
	}	
	
	function setAlias($field="",$alias="")
	{
		if ($field!="" && self::isField($field) && $alias!="") $this->Aliases[$field]=$alias;
	}
	
	function setUpdateLimit($val=true){
		$this->updLimit=($val) ? true : false ;
	}
	
	/*================================== END: BUILD QUERY SECTION ====================================*/	
	
	
	
	/*================================== QUERY EXECUTION SECTION ====================================*/	
	
	function Save($data=array(),$inject=true)	{
		$this->Data=(count($data) > 0 )  ? $data : $this->Data ;
		$sql="SHOW COLUMNS FROM `{$this->Table}` WHERE Type LIKE 'timestamp' AND `Default`!='CURRENT_TIMESTAMP'";
		$res=$this->Query($sql);
		$row=$this->GetRow($res);
			if ($row['Field']!="") {
				$inserts[]="`{$row['Field']}`=NULL";	
			}
			foreach ((array)$this->Data as $field=>$value){
				if(self::isField($field)){
					$inserts[] = "`$field`='".self::Clean(self::arrayToString($value))."'";
				}						
			} 
			foreach((array)$this->dbFunctionFields as $field=>$value){
				$inserts[]  ="`$field`=$value";
			}
			// adds a store Id if there is one.
			if(self::isField('store_id')){
				$inserts['store_id'] = $_SERVER['SERVER_NAME'];
			}
		$updates=$inserts;
			
			if ($row['Field'] != "") {				
				array_shift($updates);	
			}		
	
		$this->SQL="INSERT INTO `{$this->Table}` SET ".join(',',$inserts).' ON DUPLICATE KEY UPDATE '.join(',',$updates);
		//PR::r($this->SQL);
			if ($inject){
				$this->Query($this->SQL);
				if ($this->GeneratedError()){
					$this->Error($this->GetError());
					return false;
				} else {
					$columnSql="SHOW COLUMNS FROM `{$this->Table}` WHERE Extra='auto_increment'";
					$columnRs=$this->Query($columnSql);
					$columnRow=$this->GetRow($columnRs);					
					$idSql=" SELECT `{$columnRow['Field']}` FROM `{$this->Table}` WHERE ".join(' AND ',$updates)." ORDER BY `{$columnRow['Field']}` DESC LIMIT 1";
					#PR::e($idSql,'idSql');					
					$idRow=$this->FetchRow($idSql);					
					$this->Id=$idRow[$columnRow['Field']];	
					return true;
				}
			}	
	}
	function Update()
	{
		$this->forceUpdate=true;
		$this->Save();
	}
		
	function Load($execute=true) // gather all records based on $this->SQL
	{
		$this->ParseForLoad();
		$this->WhereClause=($this->WhereClause!=" WHERE 1=2 ")  ? $this->WhereClause : "" ;
		if ($this->selectVars!=""){
			$this->SQL=" SELECT {$this->selectVars} FROM `{$this->Table}` {$this->WhereClause} {$this->Order} {$this->Limits} ";
			if($execute){				
				$this->result=$this->Query($this->SQL);
					if ($this->GeneratedError()){
						self::GetError($this->GetError());				
					} else {
						$this->isLoaded= true;
						return $this->result;
					}
				return $this->result;
			} else {
				return $this->SQL;
			}
		}
	}
	
	function ThisRow(){		
		#if (1!=2){
			$this->Load();
		#} else {
			return mysql_fetch_assoc($this->result);
		#}
	}
	

	function EachRow()
	{
		if(!$this->isLoaded) {
			$this->Load();
		} else {
			return $this->GetRow($this->result);
		}
		
	}
	
	function Delete()
	{
		
	}
	/*================================== END: QUERY EXECUTION SECTION ====================================*/
	
	/*==================================  DATABASE RESPONSE SECTION ====================================*/	
		
	function GetId()
	{
		return $this->GetID($this->SQL);
	}	
	
	function CheckError($showSql=false)
	{
			if ($this->GeneratedError()){
				$this->Error=true;
				$this->Errors[]=$this->GetError();
			}
	}	

/*	function GetError($showSql=false)
	{
		$this->Error= true;
		$this->ErrMsg=$this->db->GetError();
	}*/
	
	function GetTableInfo()
	{
		return "";
	}
	
			
		
	/*==================================  END: DATABASE RESPONSE SECTION ====================================*/	
	
	
	function applyFunction() // applys function to data AFTER it has been retrieved from database 
	{
		$args=func_num_args();
		$funcField=array_shift($args);
		if(self::isField($funcField)) $this->funcFields[$funcField]=join(',',$args);
	}
	

	function PrintSQL()
	{
		PR::r($this->SQL);
	}
	
	function GetSql()
	{
		return $this->SQL;
	}	
	
	function DisplayFields()
	{
		return $this->Fields;
	}	

	function DisplayData()
	{
		return $this->Data;
	}		



	
	function GetSqlType()
	{
		return $this->Command;
	}
	
	function DisplayTables($obj="")
	{
		/*displays php code for insertion into given table*/
		$obj=($obj!="")  ? $obj : $this->Table ;
		if (!headers_sent()){
			header("Content-Type: text/plain");
		}
		$string= '$'.$this->Table.'=';
		echo str_pad($string,30,' ')."array();".chr(13)."\n\r";
		foreach ($this->Fields as $field){
			$string='$'.$obj.'[\''.$field.'\']=';
			echo str_pad($string,30,' ')."'';" .chr(13)."\n\r";
		}
		echo '$sql->Save($'.$this->Table.',false);'.chr(13)."\n\r";
		echo '$sql->PrintSQL();'.chr(13)."\n\r";
	}	
	
}

