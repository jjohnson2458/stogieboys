<?php
class SQLTable  
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
	const self =		__CLASS__;
	
	
	function SQLTable($db,$table="",$whereField="",$equals="")   
	{
		$this->db=				$db;
		if ($table=="") return false; 
		$this->Table=			self::Clean($table);		
		unset($this->Fields);
		$this->isLoaded=		false;
		$this->Error=			false;
		$this->Fields=			$this->db->GetFields($this->Table);		
		$this->WhereClause = 	false;
		$this->updLimit=		true;
		$this->result=			false;
		$this->selectVars=		'*';
		global					$cr;
	    global					$br;
		
		
		/* connect to data base. this class assumes a db connection of $db */
		if ($whereField!="" && $equals!="") {
			$this->Where($whereField,$equals);
		}
		

	}
	
	function isField($field)
	{
		return (in_array($field,$this->Fields))  ? true : false ;
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

	function Where($field = "", $value = "", $operator = ' = ', $and = true)
	{
		if ($value != "") {
			if (self::isField($field)){				
				$this->Wheres[] = " `" . $this->Clean($field) . "` $operator '" . $this->Clean($value) . "' ";
			}
		 } else {
		 	$this->Wheres[] = " " . $this->Clean($field) . " ";			
		 }
		
		$join = ($and)  ? ' AND ' : ' OR ' ; 
		if (count($this->Wheres) > 0) { 
			$this->WhereClause =  ' WHERE ' . join($join, $this->Wheres);			
		} else {
			$this->WhereClause = "";
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
		return mysql_real_escape_string($data,$this->db->conn);
	}	
	
	function setAlias($field="",$alias="")
	{
		if ($field!="" && self::isField($field) && $alias!="") $this->Aliases[$field]=$alias;
	}
	
	function setUpdateLimit($val=true){
		$this->updLimit=($val) ? true : false ;
	}
	
	function Insert($array, $replace = false){
		$command = ($replace)  ? 'REPLACE ' : 'INSERT ' ;
		$fields = array();
		$values = array();
		foreach ((array)$array as $field => $value) {
			if ($field != "" && self::isField($field)) {
				$fields[] = '`' . $field . '`';
				$values[] = mysql_real_escape_string($value);
			}
			
		}
		$this->SQL = $command . 'INTO ' . $this->Table  . '(' . AR::join($fields) . ') VALUES (' . AR::join(Quote::add($values)) . ')'; 
		return $this->db->GetID($this->SQL);	

	}
	
	function Replace($array){
		return $this->Insert($array, true);	
	}
	
	/*================================== END: BUILD QUERY SECTION ====================================*/	
	
	
	
	/*================================== QUERY EXECUTION SECTION ====================================*/	
	
	function Save($data=array(),$inject=true)	{
		$this->Data=(count($data) > 0 )  ? $data : $this->Data ;
		$sql="SHOW COLUMNS FROM `{$this->Table}` WHERE Type LIKE 'timestamp' AND `Default`!='CURRENT_TIMESTAMP'";
		#$this->db->Connect();
		$res=$this->db->Query($sql);
		$row=$this->db->GetRow($res);
		
			if ($row['Field']!="") {
				$inserts[]="`{$row['Field']}`=NULL";	
			}
			foreach ((array)$this->Data as $field=>$value){
				if(self::isField($field)){
					$inserts[]="`$field`='".self::Clean(self::arrayToString($value))."'";
				}						
			} 
			foreach((array)$this->dbFunctionFields as $field=>$value){
				$inserts[]="`$field`=$value";
			}			
		$updates=$inserts;
			
			if ($row['Field']!="") {				
				array_shift($updates);	
			}		
	
		$this->SQL="INSERT INTO `{$this->Table}` SET ".join(',',$inserts).' ON DUPLICATE KEY UPDATE '.join(',',$updates);			
			if ($inject){
				$this->db->Query($this->SQL);
				if ($this->db->GeneratedError()){
					$this->Error($this->db->GetError());
					return false;
				} else {
					$columnSql="SHOW COLUMNS FROM `{$this->Table}` WHERE Extra='auto_increment'";
					$columnRs=$this->db->Query($columnSql);
					$columnRow=$this->db->GetRow($columnRs);					
					$idSql=" SELECT `{$columnRow['Field']}` FROM `{$this->Table}` WHERE ".join(' AND ',$updates)." ORDER BY `{$columnRow['Field']}` DESC LIMIT 1";
					#PR::e($idSql,'idSql');					
					$idRow=$this->db->FetchRow($idSql);					
					$this->Id=$idRow[$columnRow['Field']];	
					return true;
				}
			}	
	}
	
	function Write($data=array(),$inject=true){
		$this->Data=(count($data) > 0 )  ? $data : $this->Data ;
			foreach ((array)$this->Data as $field=>$value){
				if(self::isField($field)){
					$inserts[]="`$field`='".self::Clean(self::arrayToString($value))."'";
				}						
			} 
			foreach((array)$this->dbFunctionFields as $field=>$value){
				$inserts[]="`$field`=$value";
			}
			
			if(self::isField('date_added')){
				$inserts['date_added'] = "`date_added` = NOW() " ; //getLocalTime(); 
			}
			
			$updates = $inserts;
			
			if(isset($updates['date_added'])){
				unset($updates['date_added']);
			}
			if(self::isField('date_modified')){
				$updates['date_modified'] = "`date_modified` = NOW() " ; //getLocalTime();
			}
		if(count($inserts) > 0){
			$this->SQL = "INSERT INTO `{$this->Table}` SET " . join(',', $inserts) .' ON DUPLICATE KEY UPDATE '.join(',',$updates);
		}
		
			if ($inject){
				$this->db->Query($this->SQL);
				if ($this->db->GeneratedError()){
					$this->Error($this->db->GetError());
					return false;
				} else {
					$columnSql="SHOW COLUMNS FROM `{$this->Table}` WHERE Extra='auto_increment'";
					$columnRs=$this->db->Query($columnSql);
					$columnRow=$this->db->GetRow($columnRs);					
					//$idSql=" SELECT `{$columnRow['Field']}` FROM `{$this->Table}` WHERE ".join(' AND ',$updates)." ORDER BY `{$columnRow['Field']}` DESC LIMIT 1";
										
					//$idRow=$this->db->FetchRow($idSql);					
					//$this->Id = $idRow[$columnRow['Field']];	
					return $this->db->GetID();
				}
			}	
							
	}	
		
	
	function Update($data = false, $inject = false)
	{
		if($this->WhereClause){
			$this->Data = ($data && is_array($data))  ? $data : $this->Data ;
			$updates = array();
			foreach($this->Data as $field => $value){
				if(self::isField($field)){
					$updates[$field] = $this->Clean($value);
				}
			} 
			if (count($updates) > 0) {
				$this->SQL = 'UPDATE `' . $this->Table . '` SET '
				. AR::join($updates) . ' ' . $this->WhereClause;
				if ($inject) {
					$this->db-Query($this->SQL);
					return $this->db->AffectedRows();
				} else {
					return ($this->SQL);
				}
				
			}
		}

	}
		
	function Load($execute=true) // gather all records based on $this->SQL
	{
		$this->ParseForLoad();
		$this->WhereClause=($this->WhereClause!=" WHERE 1=2 ")  ? $this->WhereClause : "" ;
		if ($this->selectVars!=""){
			$this->SQL=" SELECT {$this->selectVars} FROM `{$this->Table}` {$this->WhereClause} {$this->Order} {$this->Limits} ";
			if($execute){				
				$this->result=$this->db->Query($this->SQL);
					if ($this->db->GeneratedError()){
						self::GetError($this->db->GetError());				
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
			return $this->db->GetRow($this->result);
		}
		
	}
	
	function Delete()
	{
		
	}
	/*================================== END: QUERY EXECUTION SECTION ====================================*/
	
	/*==================================  DATABASE RESPONSE SECTION ====================================*/	
		
	function GetId()
	{
		return $this->db->GetID($this->SQL);
	}	
	
	function CheckError($showSql=false)
	{
			if ($this->db->GeneratedError()){
				$this->Error=true;
				$this->Errors[]=$this->db->GetError();
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
		return $this->db->SQL;
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
	
	function DisplayTables($obj="", $pad = 30)
	{
		/*displays php code for insertion into given table*/
		$obj = ($obj != "")  ? $obj : $this->Table ;
		if (!headers_sent()){
			header("Content-Type: text/plain");
		}
		$string = '$' . $this->Table;
		echo str_pad($string, $pad, ' ') . ' = ' . "array();" . chr(13) . CR;
		foreach ($this->Fields as $field){
			$string='$' . $obj . '[\'' . $field . '\'] ';
			echo str_pad($string, $pad, ' ') . " = '';" . CR;
		}
		echo '$sql->Save($' . $this->Table . ');' . CR;
		echo 'echo $sql->sql;' . chr(13) . CR;
	}	
	
}

