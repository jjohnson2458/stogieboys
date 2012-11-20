<?php
class OpenCart
{

	
	function __construct(){
		global $db;
		$this->db = $db;
		$this->config = $this->get_configuration();
		//PR::r($this->config);
		$language_sql = "SELECT language_id FROM language WHERE code = '" . $this->config['config_language'] . "'";
		$this->language_id = $this->db->GetValue($language_sql);
		//PR::r($this->language_id);
	}
	
	function get_configuration(){
		$config_sql = "SELECT * FROM `setting`
		 WHERE 1 
		 AND `group` = 'config'
		 AND store_id = '" . STORE_ID . "';";
		$config_rows = $this->db->GetAllRows($config_sql);
		

		foreach((array)$config_rows as $config_row){
			$this->config[$config_row['key']] = $config_row['value'];	
		}	
		return $this->config;
	}
	
	function get_language_id(){
		$language_sql = "SELECT language_id FROM language WHERE code = '" . $this->config['config_language'] . "'";
		$this->language_id = $this->db->GetValue($language_sql);
		
		return $language_id;
	}
	
	public function getProductCustomValue($product_id, $field_name){		
		$sql="
		SELECT
		pf.`text`
		FROM
		`product_field` pf,
		`field` f,
		`field_description` fd
		WHERE 1
		AND pf.language_id = '" . $this->language_id . "'
		AND pf.field_id = f.field_id
		AND f.field_id = fd.field_id
		AND fd.name = '" . $this->db->Sanitize($field_name) . "'
		AND pf.product_id = " . (int)$product_id;
		$row = $this->db->FetchRow($sql);
		return (isset($row['text']))  ? $row['text'] : false ;
	}
	
	public function getProductAttribute($product_id, $attribute = ""){		
		if ($attribute != ""){
			$sql="
			SELECT
			pa.`text`
			FROM
			`product_attribute` pa,
			`attribute` a,
			`attribute_description` ad
			WHERE 1
			AND pa.language_id = '" . $this->language_id . "'
			AND pa.attribute_id = a.attribute_id
			AND a.attribute_id = ad.attribute_id
			AND ad.name = '" . $this->db->Sanitize($attribute) . "'
			AND pa.product_id = " . (int)$product_id;
			//echo $sql;
			$row = $this->db->FetchRow($sql);
			return (isset($row['text']))  ? $row['text'] : false ;			
		}
	}

	// email methods: used by shipworks:
	/**
	* 
	*
	* @param $id mixed identifier of the email template
	* @param 
	* @return mixed
	* @access
	* @uses
	* @see
	*/

	function getTemplateEmail($id){
		$email_data = array('description' => array(), 'status' => '', 'special' => '0', 'track' => '0');

		$sql = "SELECT * FROM " . DB_PREFIX . "template_email WHERE id = '" . $this->db->Sanitize($id) . "'";
		$rows = $this->db->GetAllRows($sql);
		
		foreach ($rows as $result) {
			$email_data['description'][$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description']
			);

			$email_data['status'] = $result['status'];
			$email_data['special'] = $result['special'];
			$email_data['track'] = $result['track'];
		}

		return $email_data;		
	}

	function getOrderInfo($order_id){
		$order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");



		if ($order_query->num_rows) {

			$reward = 0;

			

			$order_product_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "order_product WHERE order_id = '" . (int)$order_id . "'");

		

			foreach ($order_product_query->rows as $product) {

				$reward += $product['reward'];

			}			

			

			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['shipping_country_id'] . "'");



			if ($country_query->num_rows) {

				$shipping_iso_code_2 = $country_query->row['iso_code_2'];

				$shipping_iso_code_3 = $country_query->row['iso_code_3'];

			} else {

				$shipping_iso_code_2 = '';

				$shipping_iso_code_3 = '';

			}



			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['shipping_zone_id'] . "'");



			if ($zone_query->num_rows) {

				$shipping_zone_code = $zone_query->row['code'];

			} else {

				$shipping_zone_code = '';

			}



			$country_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "country` WHERE country_id = '" . (int)$order_query->row['payment_country_id'] . "'");



			if ($country_query->num_rows) {

				$payment_iso_code_2 = $country_query->row['iso_code_2'];

				$payment_iso_code_3 = $country_query->row['iso_code_3'];

			} else {

				$payment_iso_code_2 = '';

				$payment_iso_code_3 = '';

			}



			$zone_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone` WHERE zone_id = '" . (int)$order_query->row['payment_zone_id'] . "'");



			if ($zone_query->num_rows) {

				$payment_zone_code = $zone_query->row['code'];

			} else {

				$payment_zone_code = '';

			}

		

			if ($order_query->row['affiliate_id']) {

				$affiliate_id = $order_query->row['affiliate_id'];

			} else {

				$affiliate_id = 0;

			}				

				

			$this->load->model('sale/affiliate');

				

			$affiliate_info = $this->model_sale_affiliate->getAffiliate($affiliate_id);

				

			if ($affiliate_info) {

				$affiliate_firstname = $affiliate_info['firstname'];

				$affiliate_lastname = $affiliate_info['lastname'];

			} else {

				$affiliate_firstname = '';

				$affiliate_lastname = '';				

			}



			$this->load->model('localisation/language');

			

			$language_info = $this->model_localisation_language->getLanguage($order_query->row['language_id']);

			

			if ($language_info) {

				$language_code = $language_info['code'];

				$language_filename = $language_info['filename'];

				$language_directory = $language_info['directory'];

			} else {

				$language_code = '';

				$language_filename = '';

				$language_directory = '';

			}

			

			return array(

				'order_id'                => $order_query->row['order_id'],

				'invoice_no'              => $order_query->row['invoice_no'],

				'invoice_prefix'          => $order_query->row['invoice_prefix'],

				'store_id'                => $order_query->row['store_id'],

				'store_name'              => $order_query->row['store_name'],

				'store_url'               => $order_query->row['store_url'],

				'customer_id'             => $order_query->row['customer_id'],

				'customer'                => $order_query->row['customer'],

				'customer_group_id'       => $order_query->row['customer_group_id'],

				'firstname'               => $order_query->row['firstname'],

				'lastname'                => $order_query->row['lastname'],

				'telephone'               => $order_query->row['telephone'],

				'fax'                     => $order_query->row['fax'],

				'email'                   => $order_query->row['email'],

				'shipping_firstname'      => $order_query->row['shipping_firstname'],

				'shipping_lastname'       => $order_query->row['shipping_lastname'],

				'shipping_company'        => $order_query->row['shipping_company'],

				'shipping_address_1'      => $order_query->row['shipping_address_1'],

				'shipping_address_2'      => $order_query->row['shipping_address_2'],

				'shipping_postcode'       => $order_query->row['shipping_postcode'],

				'shipping_city'           => $order_query->row['shipping_city'],

				'shipping_zone_id'        => $order_query->row['shipping_zone_id'],

				'shipping_zone'           => $order_query->row['shipping_zone'],

				'shipping_zone_code'      => $shipping_zone_code,

				'shipping_country_id'     => $order_query->row['shipping_country_id'],

				'shipping_country'        => $order_query->row['shipping_country'],

				'shipping_iso_code_2'     => $shipping_iso_code_2,

				'shipping_iso_code_3'     => $shipping_iso_code_3,

				'shipping_address_format' => $order_query->row['shipping_address_format'],

				'shipping_method'         => $order_query->row['shipping_method'],

				'shipping_code'           => $order_query->row['shipping_code'],

				'payment_firstname'       => $order_query->row['payment_firstname'],

				'payment_lastname'        => $order_query->row['payment_lastname'],

				'payment_company'         => $order_query->row['payment_company'],

				'payment_address_1'       => $order_query->row['payment_address_1'],

				'payment_address_2'       => $order_query->row['payment_address_2'],

				'payment_postcode'        => $order_query->row['payment_postcode'],

				'payment_city'            => $order_query->row['payment_city'],

				'payment_zone_id'         => $order_query->row['payment_zone_id'],

				'payment_zone'            => $order_query->row['payment_zone'],

				'payment_zone_code'       => $payment_zone_code,

				'payment_country_id'      => $order_query->row['payment_country_id'],

				'payment_country'         => $order_query->row['payment_country'],

				'payment_iso_code_2'      => $payment_iso_code_2,

				'payment_iso_code_3'      => $payment_iso_code_3,

				'payment_address_format'  => $order_query->row['payment_address_format'],

				'payment_method'          => $order_query->row['payment_method'],

				'payment_code'            => $order_query->row['payment_code'],

				'comment'                 => $order_query->row['comment'],

				'total'                   => $order_query->row['total'],

				'reward'                  => $reward,

				'order_status_id'         => $order_query->row['order_status_id'],

				'affiliate_id'            => $order_query->row['affiliate_id'],

				'affiliate_firstname'     => $affiliate_firstname,

				'affiliate_lastname'      => $affiliate_lastname,

				'commission'              => $order_query->row['commission'],

				'language_id'             => $order_query->row['language_id'],

				'language_code'           => $language_code,

				'language_filename'       => $language_filename,

				'language_directory'      => $language_directory,				

				'currency_id'             => $order_query->row['currency_id'],

				'currency_code'           => $order_query->row['currency_code'],

				'currency_value'          => $order_query->row['currency_value'],

				'ip'                      => $order_query->row['ip'],

				'forwarded_ip'            => $order_query->row['forwarded_ip'], 

				'user_agent'              => $order_query->row['user_agent'],	

				'accept_language'         => $order_query->row['accept_language'],					

				'date_added'              => $order_query->row['date_added'],

				'date_modified'           => $order_query->row['date_modified']

			);

		} else {

			return false;

		}		
	}

	function sendEmail($order, $template = "", $status = 9, $trackcode = ""){
		
	}

	function GetAdmin($type = 'username'){
		if (isset($_SESSION['user_id'])) {
			$sql = "SELECT * FROM user WHERE user_id = " . (int)$_SESSION['user_id'];			
			$result = $this->db->FetchRow($sql);
			if ($result != '') {
				switch ($type) {
					case 'username':
						return ($result['username']);
					break;

					case 'firstname':
						return $result['firstname'];
				    break;

					case 'fullname':
						return $result['firstname'] . ' ' .$result['lastname'];
					break;

					case 'userid':
						return $result['user_id'];
					break;
					default :
				}				
			}

			
		}
	}
}
