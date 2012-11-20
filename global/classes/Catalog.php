<?php
// IN DEVELOPMENT MODE - DO NOT USE
//error_reporting(E_ALL);
//include_once('../config.php');
class Catalog
{
	
	protected $where_field;
	protected $where_operator;
	protected $where_value;
	public $debug;

	/**
	*
	* @param
	* @return
	*/	
	function __construct($db, $id = "", $value = "")
	{
		$this->debug = false;
		$this->db = $db;
		$this->id = intval($id);
		if ($this->id != 0) {
			$this->where_field = $id;
		}
		$this->where_value =($value != "")  ? $db->Sanitize($value) : '' ;

		

		$this->operators = array('=', '!=', 'LIKE', '<>', '<=', '>=', '!', '>', '<');
		$this->_categories_fields = $this->db->GetFields('categories');
		$this->categories_description_fields = $this->db->GetFields('categories_description');


		$this->_products_fields = $this->db->GetFields('products');
		$this->products_description_fields = $this->db->GetFields('products_description');


			
			foreach ($this->_categories_fields as $value) {
				$key = 'c.' . $value;
				if(preg_match("/^categories\_/", $value)){					
					$this->c_with_prefixes[$key] = preg_replace("/^categories\_/", '', $value);;
				} else {
					$this->categories_fields[$key] = $value;
				}
			}

			foreach ($this->categories_description_fields as $value) {
				$key = 'cd.' . $value;
				if(preg_match("/^categories\_/", $value)){					
					$this->c_with_prefixes[$key] = preg_replace("/^categories\_/", '', $value);
				} else {
					$this->categories_fields[$key] = $value;
				}
			}
			
			foreach ($this->_products_fields as $value) {
				$key = 'p.' . $value;
				if(preg_match("/^products\_/", $value)){					
					$this->p_with_prefixes[$key] = preg_replace("/^products\_/", '', $value);;
				} else {
					$this->products_fields[$key] = $value;
				}
			}

			$this->p_with_prefixes['p.products_name1'] = 'name'; // a fix for the zen cart glitch

			foreach ($this->products_description_fields as $value) {
				$key = 'pd.' . $value;
				if(preg_match("/^products\_/", $value)){					
					$this->p_with_prefixes[$key] = preg_replace("/^products\_/", '', $value);;
				} else {
					$this->products_fields[$key] = $value;
				}
			}

		 
	}
	
	
	/**
	*
	* @param
	* @return
	*/
	public function __call($name, $args = array())
	{
		$parts			= explode('_', $name);
		$action			= trim(strtolower($parts[0])); // should be either a 'get' or 'set'
		$table			= trim(strtolower($parts[1])); // looking for categories or products
		$select_field	= trim(strtolower(AR::join(array_slice($parts, 2), '_') ));



		if ($table != 'categories' && $table != 'products') {
			return false;
		}			
		
		switch ($action) {
			case 'get':						
				$field 		= (!empty($args[0]))  ? $args[0] : $this->where_field ;
				$operator 	= (!empty($args[1]))  ? $args[1] : $this->where_operator ;		
				$value 		= (!empty($args[2]))  ? $args[2] : $this->where_value ;	
				if ($this->debug) {
					PR::r("field = $field");
					PR::r("operator= $operator");
					PR::r("value = $value");	
					PR::r("select_field = $select_field");
				}
				
				$wheres = array(1);
				$status = "0";
				if($field != "" && empty($operator)  && empty($value)){ // id only
					$wheres[] = $this->get_primary_key($table) . ' = ' . Quote::add($this->db->Sanitize($field));
					$status = 1;
				} elseif($field != "" && !empty($operator) && empty($value)) { // {some field} = {some value}
					$status = 2;
					if($parsed_field = self::parse_field($table, $field)){
						$wheres[] = $parsed_field  . ' = ' . Quote::add($this->db->Sanitize($operator));
					}
								
				} elseif($field != "" && !empty($operator) && !empty($value)) {
					$status = 3;
					if($parsed_field = self::parse_field($table, $field) && self::is_operator($operator) ){
						$status = 4;
						$wheres[] = $parsed_field  . ' ' .$operator . ' ' . Quote::add($this->db->Sanitize($value));
					}			
				} elseif($field == "") {
					//return;
				}

			$select_field = self::parse_field($table, $select_field);
			if(!$select_field){
				$select_field = ' * ';
			}
			$this->SQL = "SELECT " . $select_field. " FROM " . self::set_table_alias($table) . " " . AR::join($wheres, ' AND ');			
			$rows = $this->db->Query($this->SQL);
			if ($this->debug) {
				PR::r($status . ': ' . $this->SQL, $this->db->GetAllRows($this->SQL), $this->db->ShowError() );
			}
			$rows = $this->db->GetAllRows($this->SQL);
			if(count($rows) > 1 ){
				return $rows;
			} elseif (count($rows[0]) > 1) {
				return  $rows[0] ;	
			} elseif(count($rows[0]) == 1) {
				return array_shift($rows[0]);
			} else {
				return false;
			}
			break;

			case 'set':
			break;

			default :
		}
	}

	/**
	* returns select inputs of categories
	* @param string $name = name/id of select tag
	* @param int $default_categories_id default value for dropdown
	* @param string $attributes - any addition data for select tag
	* @return string select tag filled with product data
	*/
	function get_categories_select($default_categories_id = "", $name = "categories_id", $attributes = "")
	{		
		$categories = $this->get_categories();
		$select_array[] = 'Select Category';
		foreach($categories as $category){
			if ($category['categories_id'] != "" && $category['categories_name'] != "" && $category['categories_status'] != 0) {
				$select_array[$category['categories_id']] = $category['categories_name'] ;
			}			
		}
		PR::r($default_categories_id);
		return Input::Select($name, $select_array, $default_categories_id, $attributes);		
	}



	/**
	*
	* @param
	* @return
	*/
	public static function get_categories_json()
	{
		
	}

	/**
	* returns array of category data indexed by categories_id
	* @param
	* @return array info related to the category(s)
	*/
	public static function get_categories_data($categories_id = "")
	{
		
	}



	/**
	* returns select inputs of products
	* @param int $categories_id = category where the products are located
	* @param string $name = name/id of select tag
	* @param int default_products_id default value for dropdown
	* @param string attributes - any addition data for select tag
	* @return string select tag filled with product data
	*/
	function get_products_select($categories_id = "", $default_products_id = "", $name = "products_id", $attributes = "")
	{
		
		$products = $this->get_products('categories_id', $categories_id);
		$select_array[] = 'Select Product';
		foreach($products as $product){
			if ($product['products_name1'] != "" && $product['products_id'] != "" && $product['products_status'] != 0) {
				$select_array[$product['products_id']] = $product['products_name1'] ;
			}			
		}
		return Input::Select($name, $select_array, $default_products_id, $attributes);
	}

	/**
	* returns javascript code for ajax call to get the products select
	* @param
	* @return
	*/
	public static function get_products_script($name = 'getProducts', $id = 'this')
	{
		$js = new Javascript();
		ob_start();
			?>

			<?
		$script = ob_get_clean();
	}

	/**
	* returns array of products data indexed by categories_id
	* @param
	* @return array info related to the product(s)
	*/
	public static function get_products_data($products_id = "")
	{
		
	}



	/**
	* returns product info (including location) based on scanned barcode
	* @param
	* @return
	*/
	public static function get_sku()
	{
		
	}


	/**
	*
	* @param
	* @return
	*/
	public static function get_location()
	{
		
	}


	/**
	*
	* @param
	* @return
	*/
	public static function get_stock_info()
	{
		
	}


	/**
	*
	* @param
	* @return
	*/
	private function get_primary_key($table = "")
	{
		if ($table != "") {
			return ($table != 'categories')  ? 'p.products_id' : 'c.categories_id' ;
		}
	}

	private function set_table_alias($table = "")
	{
		if ($table == 'categories') {
			return ' categories c, categories_description cd WHERE c.categories_id = cd.categories_id AND ';
		} elseif ($table == 'products') {
			return ' products p, products_description pd WHERE p.products_id = pd.products_id AND ';
		}
	}

	function parse_field($table = "", $field = "")
	{
		if ($table == 'categories') {
			$field = preg_replace("/^categories\_/", '', $field);			
			if($result_field = array_search($field, $this->categories_fields)){
				return ($result_field);
			} elseif ($result_field = array_search($field, $this->c_with_prefixes)) {
				return ($result_field);
			} else {
				return false;
			}
			
		} elseif ($table == 'products') {
			$field = preg_replace("/^products\_/", '', $field);			
			if($result_field = array_search($field, $this->products_fields)){
				return ($result_field);
			} elseif ($result_field = array_search($field, $this->p_with_prefixes)) {
				return ($result_field);
			} else {
				return false;
			}
			
		} else {
			return false;
		}
	}

	function is_operator($operator = "")
	{
		return (in_array($operator, $this->operators))  ? true : false ;
	}
	


}
// ================================ DEV TEST AREA ================================

//$db = new MySQL($dbname, $dbhost, $dbuser, $dbpass);
//$catalog = new Catalog($db);

// PR::r($catalog->get_products_name());
// or
//PR::r($catalog->get_categories_select(22));



/*

$catalog->get_products_status($products_id); // gets products status; if categories_id is blank, will return all product statuses

$catalog->set_products_status(); // sets the products status ONLY if an id is present

$catalog->save_product(); // saves product data ONLY if products_id is present

$catalog->save_category(); // saves category data ONLY if products_id is present

*/























































