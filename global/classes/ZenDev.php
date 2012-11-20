<?php
	
class ZendDev
{
	CONST self = __CLASS__;
	
	function __construct($db, $categories_id, $products_id)
	{
		$this->db = $db;
		if($categories_id != ""){
			$this->categories_id = intval($categories_id);
		}
		if($products_id != ""){
			$this->products_id 	= intval($products_id);
		}
	}
	/**
	* 
	* @param array $data - field/value pair of where javascprt will output: field: which field; 
	*/
	function get_products($js = false, $data = array())
	{
		
	}
	
	function _get_categories($js = true)
	{
		
		$attributes = (isset($this->products_id) && $js)  ? 'onChange="getProductInfo(this.id);"' : '' ;
		
		$sql = "SELECT COUNT(products_id) as products 
		FROM products_to_categories 
		WHERE categories_id = '$categories_id'";
		$row = $this->db->FetchRow($sql);
		if($row['products'] > 0)
		{
			$cat_clause = " = '{$categories_id}'";
		}
		else 
		{
			$sql = "SELECT categories_id FROM categories WHERE parent_id = '{$categories_id}'";
			$categories_ids = $this->db->GetAllRows($sql, 'categories_id');
			$cat_clause = "IN(" . AR::join($categories_ids) . ")";
		}
		
		$sql = "SELECT
		DISTINCT(p.products_id) as products_id,
		p2c.categories_id,
		p.products_name1
		FROM
		products p
		JOIN (products_to_categories p2c)
		ON(p.products_id = p2c.products_id)
		WHERE 1
		AND p2c.categories_id $cat_clause
		AND p.products_status ='1'
		AND p.products_name1 !=''
		ORDER BY p.products_name1 ";
		$products_array = array();
		$products = $this->db->GetAllRows($sql);
		
		foreach((array)$products as $product)
		{
			$products_array[$product['products_id']] = $product['products_name1']; 	
		}
		return Input::Select('products_id', $products_array, $_GET['products_id'], $attributes);
		
	}
}