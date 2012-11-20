<?php
class MySQL { 

	var $mailme = false;
	var $conn;
	public static  $host;
	var $name;
	var $user;
	var $pass;
	var $ErrorMessage;
	
	function MySQL($dbName="",$dbHost="",$dbUser="",$dbPass=""){
		$this->host=($dbHost!="")  ? $dbHost: DB_HOST ;
		$this->name=($dbName!="")  ? $dbName: DB_NAME ;
		$this->user=($dbUser!="")  ? $dbUser: DB_USER ;
		$this->pass=($dbPass!="")  ? $dbPass: DB_PASS ;
	


		// Open a persistent connection

		$this->conn = mysql_connect($this->host,$this->user,$this->pass);
			if (!$this->conn){
				die('cannot connect to database');
			} else {
				$this->SwitchDB($this->name);				
			}
	}
	
	function Connect(){
		$this->Close();	
		$this->MySQL();		
		$this->conn=mysql_connect($this->host,$this->user,$this->pass);	 	
		return $this->conn;
	}
	
	function SwitchDB($dbname=""){
		if ($dbname!=""){
			$rs = mysql_select_db($dbname);
				if (!$rs){
					$this->Error("could not connect to {$dbname}");
					echo ("could not connect to {$dbname}");
					return false;
				} else {										
					return true;
				}
		}
	}


	function query($sql=""){		
		if ($sql!=""){
			$this->result=mysql_query($sql,$this->conn);
			if (!$this->result){				
				$this->Error($this->GetError());
				return false;
			} else {				
				$this->ID = mysql_insert_id($this->conn);
				return $this->result;
			}
		}
	}

	function sqlQuery($sql="") 
	{
		if ($sql!="") {
			return $this->query($sql);
		}
	}
	
	#function Query($sql=""){return $this->query($sql);}

	function trueQuery($sql=""){
	if ($sql!=""){
		$res = mysql_query($sql, $this->conn);
			if (!$res) {
				#if (strpos($statement,'email4johnson@gmail.com')) echo '<pre>'.mysql_errno($this->conn) . ": " . mysql_error($this->conn).'</pre>';
				if ($this->mailme) {
					mail ( $this->mailme, 'SQL Error' , "The SQL Statement [$sql] generated the following error: \n\n" . mysql_errno($this->conn) . ": " . mysql_error($this->conn) . "\n");
				} else {
					echo mysql_errno($this->conn) . ": " . mysql_error($this->conn) . "\n";
				}
				return false;
			} else {
				if ( preg_match("/^\s*INSERT/i", $sql) ) {
					$res = mysql_insert_id();
				}
				return $res;
			}
	}
}

	function NumRows($rest){
		if ($rest!=""){
			return mysql_num_rows($rest);
		} else {
			return 0;	
		}
	}

	function AffectedRows($rest = ""){
		return mysql_affected_rows($this->conn);
	}
	
	function GetFields($table){
		$this->fields=array();
		$sql=" SHOW COLUMNS FROM `{$table}`";
		$res=$this->query($sql);
			while ($row=$this->GetRow($res)){			
			$this->fields[]=$row['Field'];
			}
		
		return $this->fields;
		}

	function GetRow($rset)
	{
		// Return the next row or null if done
		
		if ($rset) {
			$row = mysql_fetch_assoc($rset);
			return $row;
		}
		return null;		
	}
	
	function FetchRow($query){
		$rs=$this->Query($query);
		$row=$this->GetRow($rs);
		return $row;
	}
/*
* returns a single (first) element of the row returned by the db.
*/
function GetValue($sql){
	$row=$this->FetchRow($sql);
	if (!empty($row)){
		return array_shift($row);
	}	
}	
	function GetJson($query){
		$rs=$this->Query($query);
		if ($rs){
			$results=array();
				while ($row=$this->GetRow($rs)){
					$results=array_push($results,$row);
				}
			$json=new JSON();
			return $json->encode($results);
		}
		
		$row=$this->GetRow($rs);
		return $row;		
	}
	
	function EachRow($sql){
	// same as GetRow, but excutes query if none has been excuted already
		if (!$this->result){
			$this->result=$this->Query($sql);
		}
		
		if ($this->result){
			return $this->GetRow($this->result);
		} 
	}
	
	function Sanitize($text="")
	{
		$magicQuotes=(get_magic_quotes_gpc())  ? true : false ;
		$data=($magicQuotes)  ? stripslashes($text) : $text ;
		return mysql_real_escape_string($data,$this->conn);
	}
			
	function GetObject($rset)
	{
		// Return the next row or null if done
		if ($rset) {
			$obj = mysql_fetch_object($rset);
			return $obj;
		}

		return null;		
	}
	
	function GetID($sql="")
	{
		if ($sql!=""){
			$this->Query($sql);
		}
		$this->ID = mysql_insert_id($this->conn);
		return $this->ID;
		
	}
	
	function GetAllRows($sql,$field=""){
		/*returns all values from sql statement in an array*/
		$rs=$this->Query($sql);		
		$rows=array();	
			while ($row=$this->GetRow($rs)){
				if ($field!=""){
					array_push($rows,$row[$field]);
					#print__r($row['field']);
				} else {
					array_push($rows,$row);
				}
			} 
		return $rows;
	}
	
	/**
	* returns all values from sql statement in an array
	* indexed by $field or the first column it receives (usually the primary key)
	* @param string $sql query statement
	* @param string $field - column in which to index
	* @return array $query result as an associative array
	*/
	function GetIndexRows($sql = "", $field = ""){
		$rows = array();
		if($sql != ""){
			$rs = $this->Query($sql);		
			while ($row = $this->GetRow($rs)){
				if ($field != ""){
					$key = array_shift($row);
					$rows[$key] = reset($row);					
				} else {
					$_row = $row;
					$key = reset($row);
					$rows[$key] = $_row;
				}
			} 
		return $rows;		
		}
	}
	
	function showQuery(){
		echo  "<pre>".$this->SQL."</pre>";
	}
	
	function Error($error=""){
		if ($error!="") {
			#$this->Error=true;
			$this->errors[]=$error;
			$this->ErrorMessage=join('<br>',(array)$this->errors);
		}
	}
	
	function GetError($show_query=false){
		if($this->conn){
			$error= mysql_error($this->conn);
		} else {
			$error= "No Error -> Database Closed";
		}
		return (($show_query===true)? "QUERY:{$this->SQL}\n<hr>" : '').$error;
	}
	
	function GeneratedError(){
		$error = false;
		if($this->conn !== false) { 
			$error = (mysql_error($this->conn) != ''); 
		}
		return $error; 
	}	
	
	function isError(){
		return GeneratedError();
	}		
	
	function dbError(){
		return GeneratedError();
	}			
	
	function ShowError($show_query=false){
		if (!$this->GeneratedError()) return false;
		print "<pre>".$this->GetError($show_query)."</pre>";
	}	
	function RunSQLFile($filename){
		if(file_exists($filename)){
			$file = @fopen($filename, 'r');
			while (!feof($file)) {
                $line = @fgets($file, 32768);
                $line = trim($line, "\t\r\n ;");
                if(($line!='') && (substr($line, 0, 1)!='-')){
                    $this->query($line);
					#PR::r($line);
                    if($this->GeneratedError()){
					#PR::r($this->GetError());
                        @fclose($file);
                        return false;
                    }
                }
			}
			@fclose($file);
			return true;
		}
		echo 'file does not exists';
		return false;
	}
	
	function LoadSql($filename){
		if(file_exists($filename)){			
			$this->query(file_get_contents($filename));
			if($this->GeneratedError()) PR::r($this->GetError());
		}
	}
	
	function Close(){
		if ($this->conn){
			mysql_close($this->conn);
		}
		$this->conn=false;
	}
	
	/*the following functions are used for the insert/update records functions */
	
	function SetTable($table){
		$this->table=$table;
	}
	
	function FieldsSelect($table,$data)	{
		$fields=$this->GetFields($table);
		foreach ((array) $fields as $field=>$value){	
			if (in_array($field,$existing_fields)){
				$this->return_fields[$field]=$value;
			}	
		}
		return $this->return_fields;
	}
	
	function WhereClause($field,$value){
		$this->whereclauses[][$field]=$value;
	}
	
/*	function parseWhereClauses(){
	$key=key($whereclause);
	$where=" WHERE $key='$whereclause[$key]'";
	$sql=" SELECT * FROM $table WHERE $key=?";
	
	$res=$db->query($sql,$whereclause[$key]);
	
		foreach ($this->whereclauses as $whereclause){
			
		}
			
	}*/
	
	function Save($data=array(),$whereclause="",$inject=true){
		$key=key($whereclause);
		$where=($key!="")  ?  " WHERE `$key`='$whereclause[$key]'"  : '' ;
		$sql=" SELECT * FROM  `{$this->$table}` WHERE $key=?";
		
		$result=$db->query($sql,$whereclause[$key]);
		$row=$db->fetch_row($result);
		$this->Command=(empty($row))  ? ' INSERT INTO ' : ' UPDATE ' ;
		$this->Condition=(empty($row))  ? '' : '$whereclause LIMIT 1' ;
		
		$sql=
		$this->Command."`{$table}` SET ".sqlJoin(FieldSelect($this->table,$data)).$this->Condition;	
		return ($sql);
				
	}	
	
	function get_db_name(){	   
		return $this->name;
	}
	
	function get_db_host(){	   
		return $this->host;
	}
	function get_db_user(){	   
		return $this->user;
	}
	function get_db_pass(){	   
		return $this->pass;
	}



}