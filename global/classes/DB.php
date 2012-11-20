<?php
error_reporting(E_ALL);

class DB
{
	/*
	* new SQL safe Database layer needed for the MVC framework class
	* @package jworks
	*/	
	
	var $_select;
	var $_where;
	var $_insert;
	var $_delete;
	var $_replace;
	var $fields = array();
	var $table = "";
	
	var $conn;
	var $dbhost;
	var $dbname;
	var $dbuser;
	var $dbpass;
	var $type;
	var $pdo;
	
	/*
	* @param
	* @param
	* @return 
	* @package jworks
	*/		
	
	function __construct($dbName = "", $dbHost = "", $dbUser = "", $dbPass = "", $dbType = 'mysql'){
		$this->dbhost = ($dbHost != "")  ? $dbHost : DB_HOST ;
		$this->dbname = ($dbName != "")  ? $dbName : DB_NAME ;
		$this->dbuser = ($dbUser != "")  ? $dbUser : DB_USER ;
		$this->dbpass = ($dbPass != "")  ? $dbPass : DB_PASS ;
		$this->dbtype = ($dbType != "")  ? $dbType : 'mysql' ;
		
		// Open a PDO connection
		$this->pdo_string = $this->dbtype . 'host=' . $this->dbhost . ';dbname=' . $this->dbname;
		try{
			$this->pdo = new PDO($this->pod_string, $this->dbuser, $this->dbpass); 
		} catch (PDOException $e){
			trigger_error('cannot connect to database @ ' . $this->host . 'using PDO: ' . $e->getMessage());
		}
		
		$this->whereclause 		= false;
		$this->joinclause 		= false;
		$this->groupclause 		= false;
		$this->limitclause 		= false;
		$this->fieldset 		= false;				
		
	}
	
	/*
	* @param
	* @param
	* @return 
	* @package jworks
	*/		
	
	public static function __call( $name = "", $args = array() ){
		
	}	
	
	/*
	* @param
	* @param
	* @return 
	* @package jworks
	*/		
	
	public static function select($data = array()){
		
	}		

	
	/*
	* @param
	* @param
	* @return 
	* @package jworks
	*/		
	
	public static function where($data = array()){
		
	}			
	
	/*
	* @param
	* @param
	* @return 
	* @package jworks
	*/		
	
	public static function update(){
		
	}			
	
	
	
	
	/*
	* @param
	* @param
	* @return 
	* @package jworks
	*/		
	
	public static function delete(){
		
	}			
	
		
	
	
	
	/*
	* @param
	* @param
	* @return 
	* @package jworks
	*/		
	
	public static function insert(){
		
	}			
	
			
	
	
	
	
	/*
	* @param
	* @param
	* @return 
	* @package jworks
	*/		
	
	public static function replace(){
		
	}			
	
		
	
	/*
	* @param
	* @param
	* @return 
	* @package jworks
	*/		
	
	public static function getFields(){
		
	}			
		
	
	
	
	/*
	* @param
	* @param
	* @return 
	* @package jworks
	*/		
	
	public static function getFieldsTypes(){
		
	}			
		
	
	/*
	* @param
	* @param
	* @return 
	* @package jworks
	*/		
	
	public static function Query($sql = ""){
		
	}			
		
			
	private function _query($sql = ""){
		
	}			
			
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}