<?php

class Coupon
{
	
	protected $coupon_id;
	protected $has_restriction;
	protected $is_redeemed;
	protected $redemption_id;
	
	public $coupons;
	public $coupons_restriction;
	public $coupon_restrict;
	
	
	/**
	* initiate a db connection; set loads coupon info if necessary
	* @param string $coupon_id : if applied this class will load the data in the corresponding arrays
	*/	
	function __construct($coupon_id = ""){
		if(!$this->db){
			$this->db = new MySQL(DB_NAME, DB_HOST, DB_USER, DB_PASS);	
		}
		if($this->get_coupon_data($coupon_id)){
			$this->coupon_id = $this->coupon_id;			
		} else {			
			// set_defaults:
			$this->coupon_id							= false;
			$this->coupons['coupon_start_date'] 		= RIGHTNOW;
			$this->coupons['coupon_start_date_epoch'] 	= time();
			$this->coupons['coupon_expire_date'] 		= date(DEFAULT_DATE_FORMAT, strtotime('+1 year'));
			$this->coupons['coupon_expire_date_epoch'] 	= strtotime('+1 year');	
			$this->coupons['uses_per_coupon'] 			= 1;
			$this->coupons['uses_per_user'] 			= 1;
			$this->coupons['coupon_active'] 			= 'Y';
			
			$this->coupons_description['language_id'] 	= (isset($_SESSION['languages_id']))
														? $_SESSION['languages_id'] : 1 ;
			$this->coupon_restrict 						= array();
			$this->has_restriction						= false;
			$this->is_redeemed							= false;
		}	 	
		
		
	}
	
	/**
	* loads coupon data from db
	* @param int $coupon_id
	* @return mixed false if no record found coupon data if true;
	*/
	function get_coupon_data($coupon_id = ""){
		if($coupon_id != ""){
			$sql = "SELECT * FROM coupons WHERE coupon_id = '" . intval($coupon_id) . "' ";
			$this->coupons = $this->db->FetchRow($sql);
			if(empty($this->coupons)){
				return false;	
			} else {
				// get description
				$this->coupon_id = $this->coupons['coupon_id'];
				$this->coupon_code = $this->coupons['coupon_code'];
				$sql = "SELECT * FROM coupons_description WHERE coupon_id = '{$this->coupon_id}'";	
				$this->coupons_description = $this->db->FetchRow($sql);	
				
				// get restriction data (if any)
				$sql = "SELECT * FROM coupon_restrict WHERE coupon_id = '{$this->coupon_id}'";
				$this->coupon_restrict = $this->db->FetchRow($sql);
				$this->has_restriction = (!empty($this->coupon_restrict)) ? true : false ;
				
				// get redemptions if any (we'll need this for the reset_coupon method
				$sql = "SELECT * FROM coupon_redeem_track WHERE coupon_id = '{$this->coupon_id}'";
				$this->redemption = $this->db->FetchRow($sql);
				$this->is_redeemed = (!empty($this->redemption)) ? true : false ;
				if(!empty($this->redemption)){
					$this->redemption_id = $this->redemption['unique_id'];	
				}
			}			
		} else {
			return false;	
		}
	}	
	
	/**
	* sets coupon amount
	* @param float $amount
	* @return mixed
	*/
	function set_amount($amount = 0){
		if($amount != 0){
			$this->coupons['coupon_amount'] = (float)$amount;	
		}
	}

	
	
	
	/**
	* sets minimun amount
	* @param float $amount
	* @return mixed
	*/
	function set_minumum_order($amount = 0){
		if($amount != 0){
			$this->coupons['coupon_minimum_order'] = (float)$amount;	
		}
	}	
	
	/**
	* sets start date
	* @param string $date
	* @return mixed
	*/
	function set_start_date($date = ""){
		$date = ($date != "")  ? Date::convert($date) : Date::now() ;
		if(strtotime($date)){			
			$this->coupons['coupon_start_date'] 		= $date;
			$this->coupons['coupon_start_date_epoch'] 	= strtotime($date);	
		}
	}	
	
	/**
	* sets end date
	* @param string $date using valid strtotime string
	* @return mixed
	*/
	function set_end_date($add_time = '+1 year'){		

		if(strtotime($add_time)){
			$this->coupons['coupon_expire_date'] 		= date('Y-m-d H:i:s', strtotime($add_time) );
			$this->coupons['coupon_expire_date_epoch'] 	= strtotime($add_time);	
		}
	}		
		
	/**
	* sets users per coupon
	* @params int $uses
	*
	*/
	function set_uses_per_coupon($uses = 1){
			$this->coupons['uses_per_coupon'];
	}
	
	/**
	* sets users per user
	* @params int $uses
	*
	*/
	function set_uses_per_user($uses = 1){
			$this->coupons['uses_per_user'];
	}	
	
	
	/**
	* sets products to restrict/accept
	* @param int products_id
	* @return
	*/
	function set_products_id($products_id = ""){
			if($products_id = ""){
				$this->coupons['restrict_to_products'] 	= intval($products_id);
				$this->has_restriction 					= true;	
			}
	}	
	
	
	/**
	* sets categories_id to restrict/accept
	* @param int categories_id
	* @return
	*/
	function set_categories_id($categories_id = ""){
			if($categories_id = ""){
				$this->coupons['restrict_to_categories'] = intval($categories_id);
				$this->has_restriction 		  			 = true;	
			}
	}	
	
	
	/**
	* sets customers to restrict/accept
	* @param int categories_id
	* @return
	*/
	function set_customers_id($customers_id = ""){
			if($customers_id = ""){
				$this->coupons['restrict_to_customers'] = intval($customers_id);	
			}
	}	



			
	
	/**
	* set coupon name
	* @param string $name
	*
	*/
	function set_coupon_name($name = ""){
		if($name != ""){
			$this->coupons_description['coupon_name'] = $name;	
		}	
	}	
		
	
	/**
	* set coupon description
	* @param string $name
	*
	*/
	function set_coupon_description($name = ""){
		if($name != ""){
			$this->coupons_description['coupon_description'] = $name;	
		}	
	}	
			
	/**
	* sets the coupon for restriction true/false
	* @param bool $bool
	*
	*/
	function restrict($bool = true){
		$this->coupon_restrict['coupon_restrict'] 	= ($bool)  ? 	'Y' 	: 'N' ;
		$this->is_restricted 						= ($bool)  ? 	true 	: false ;
		$this->has_restriction 						= true;
	}	
		
	/**
	*
	* inserts or updates coupon data 
	* @param string $code 	
	* @see generate()
	* return mixed: if bool set to false, query is returned, if true, query result 
	*/
	function save($code, $bool = true){
		$this->coupons['coupon_code'] = $code;
		$this->sql = new SQLTable($this->db, 'coupons');
		if(!$this->coupon_id || $this->coupon_id == ''){
			$this->coupons['date_created'] 				= RIGHTNOW;
			$this->coupons['date_created_epoch'] 		= time();			
			$this->coupon_id = $this->sql->Insert($this->coupons);
			$this->coupons_sql = $this->sql->SQL;
			
			if( $this->coupon_id ){
				$this->coupons_description['coupon_id'] = $this->coupon_id;
				$this->coupons_description['coupon_name'] = ($this->coupons_description['coupon_name'] != "")  
															? $this->coupons_description['coupon_name'] 
															: $this->coupon_code ;
															
				$this->coupons_description['coupon_description'] = 
				( $this->coupons_description['coupon_description'] != "")  
				? $this->coupons_description['coupon_description'] 
				: $this->coupons_description['coupon_name'] ;
				$this->sql = new SQLTable($this->db, 'coupons_description');
				$this->sql->Insert($this->coupons_description);
				
				if($this->has_restriction){
						
				}
				
				
			}
		} else {
			$this->coupons['date_modified'] = RIGHTNOW;			
			$this->sql = new SQLTable($this->db, 'coupons');
			$this->sql->Where('coupon_id', $this->coupon_id);
			$this->sql->Update($this->coupons);
			
			$this->sql = new SQLTable($this->db, 'coupons_description');
			$this->sql->Where('coupon_id', $this->coupon_id);
			$this->sql->Update($this->coupons_description);	
			if($this->has_restriction){
				$this->sql = new SQLTable($this->db, 'coupon_restrict');
				$this->sql->Where('coupon_id', $this->coupon_id);
				$this->sql->Update($this->coupon_restrict);
			}
				
		}
		
	}		
	

	/**
	* generates a set of codes
	* @param string $prefix - the abbrveation to be used before the code
	* @param int $code_length string length of the code and the abbreviation
	* @return string the coupon code
	*/
	
	function generate($prefix = "", $code_length = 10){
		
		if (!$this->all_coupons) {
			$sql = "SELECT coupon_code FROM coupons";
			#$this->all_coupons = $db->GetAllRows($sql, 'coupon_code');			
		}

		static $codes = array();
		$salt = 'y3v4hq394yfg8gw8hp#(%U(#*(@!NFAIAU(SR###opsiehfo@`hglsihtgoshtg3';	
		do{
			$code = $prefix . substr(md5($salt . time() . rand(10000,99999)) , 0, $code_length);
		} while (in_array($code, $codes));
		$codes[] = $code;
		$this->coupon_code = $code;
		$this->save($code);	
		return $code;
	} 
	
	/**
	* resets a used coupon
	*/
	function coupon_reset($coupon_code = ""){
		
	}

}