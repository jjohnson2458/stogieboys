<?php

class Lists
{
	/**
	* @package global
	* @example:
	$list = new List($db, $sql);
	echo $list;
	* @todo: add search / sort features;
	*/ 
	
	/**
	* in the event the server is not set up to handle late static bindings:
	*/
	CONST self = __CLASS__;
	
	var $db;
	private $sql;	
	var $ipp;	
	var $ppp;	
	var $table_css;
	var $header_css;
	var $row_css;
			
	var $header;
	var $fields;	
	var $dark_color;
	var $light_color;		
	var $selected;
	var $highlight;	
	var $link_url;
	var $page_url;
	
	
	/**
	* make sure to have no where clause or order by to the $sql statement. 
	* this will be done later inside this class
	*
	*/	
	function __construct($db, $sql = "", $header = array()){
		if(!session_id()){
			session_start();
		}
		if(!$db){
			return false;	
		} else {
			$this->db = $db;
		}	
		
		if(!$sql){
			return false;	
		} else {
			$this->SQL = $sql;
		}
		
		
		if($header){
			$this->header = $header;
		}
		
		$this->where 		= "";
		$this->order_by 	= "";
		$this->action		= 'action';
		$this->action_value = 'clear';
		$this->ipp			= 20;
		$this->ppp			= 10;
		$this->fields		= array();
		$this->light_color	= 'ffffff';
		$this->dark_color	= 'eaf3fb';
		$this->switch_color = true;
		$this->highlight	= 'ff0000';
		$this->link_url		=  $_SERVER['PHP_SELF'];	
		$this->hidden_fields= array();
		$this->sum_fields	= array();
		$this->page_url		= '';
		//PR::r($this->SQL);
	}
	
	/**
	*
	*
	*/
	function set_header($header = array()){		
		if(is_array($header)){
			$this->header = $header;	
		}
	} 
	
	
	/**
	*
	*
	*/
	function items_per_page($ipp = 20){		
		$this->ipp = (intval($ipp) !=0 )  ? intval($ipp) : 20 ;
	} 
		
	
	/**
	*
	*
	*/
	function pages_per_page($ppp = 20){
		$this->ppp = (intval($ppp) !=0 )  ? intval($ppp) : 20 ;	
	} 
		
	/**
	*
	*
	*/
	function apply_function(){
		$args = func_get_args();
		$field = $args[0];				
		$this->fields[$field]['function'] = $args[1];
		for($i = 2; $i <= count($args) - 1; $i++){		
			$this->fields[$field]['arguments'][] = CSV::_addQuotes($args[$i]);
		}	
	} 
		

		
	/**
	*
	*
	*/
	function table_css($css = ""){		
		if($css != ""){
			$this->table_css[] = $css;	
		}		
	} 
		

		
	/**
	*
	*
	*/
	function row_css($css = array()){		
		if(is_array($css)){
			$this->row_css = $css;	
		}		
	} 
				
	/**
	*
	*
	*/
	function light_color($light = "FFFFFF"){		
		if(self::is_color($light)){
			$this->light_color = $light;
		}
	} 
	
	
	/**
	*
	*
	*/
	function dark_color($dark = "CCCCCC"){		
		if(self::is_color($dark)){
			$this->dark_color = $dark;
		}
	} 
			
	
	/**
	*
	*
	*/
	function selected($selected = ""){		
		if(self::is_color($selected)){
			$this->selected = $selected;
		}
	} 
			
	
	/**
	*
	*
	*/
	function hightlight($highlight = ""){		
		if(self::is_color($highlight)){
			$this->highlight = $highlight;
		}
	} 
	
	
	/**
	*
	*
	*/
	function switch_color($switch_color = true){		
			$this->switch_color = ($switch_color == true)  ? true : false ;
	} 
		
	
	/**
	*
	*
	*/	
	function is_color($color){
		$pattern = "/[A-Fa-f0-9]/s";
		return ( preg_match($pattern, $color) )  ? $color : '' ;
	} 
					
	/**
	*
	*
	*/
	function header_css($css = array()){		
		if(is_array($css)){
			$this->header_css = $css;	
		}			
	} 
		

	/**
	*
	*
	*/
	function set_row_index($row_index = ""){		
		if($row_index != ""){
			$this->row_index = $row_index;	
		}			
	} 
	
	/**
	*
	*
	*/
	function set_link_url($link_url = ""){		
		if($link_url != ""){
			$this->link_url = $link_url;	
		}			
	} 	
	
	
	/**
	*
	*
	*/
	function set_page_url($page_url = ""){		
		if($page_url != ""){
			$this->page_url = $page_url;	
		}			
	} 	
	
	
	/**
	*
	*
	*/
	function hide_field($field = ""){		
		if($field != ""){
			$this->hidden_fields[] = $field;	
		}			
	} 		
				
	/**
	*
	*
	*/
	function __toString(){
		$html  = $this->_build();	
		return $html;	
	} 
	/**
	* will cause a the sum of a field to be provided at the bottom if applicable
	*
	*/	
	function apply_sum_total($field = "")
	{
		if($field != ''){
			$this->sum_fields[] = $field;
		}
	}
		
	/**
	*
	*
	*/
	function show_search($search = true){
		$this->show_search = ($search != false)  ? true : false ;
	}	

	/**
	*
	*
	*/
	function show_search_fields($fields = array()){
		$this->show_search_fields = array_unique(array_merge($this->show_search_fields, $fields));
	}	
	
	
	/**
	* applies a function to a field of data BEFORE interaction with a database
	* in other words, this function is applied to a string BEFORE it is used in the where clause.
	* $arg[0] string search field
	* $arg[1] function
	* $arg[2, 3, etc] mixed other variables to be used within function
	*/
	function apply_search_function(){
		$args = func_get_args();
		$field = $args[0];
		if($arg[1] != ""){		
			$this->apply_search_function[$field]['funcion'] = $arg[1];		
			for($i = 2; $i <= count($args) - 1; $i++){		
				$this->fields[$field]['arguments'][] = Quote::add($args[$i]);
			}
		}			
	}	
	
	/**
	*
	*
	*/
	function where($where = ""){	
		$this->where = $where;
	}	
		
	/**
	*
	*
	*/
	function _build(){

		$this->result = $this->db->Query($this->SQL);
		
		$this->total_rows = $this->db->NumRows($this->result);
		$total_pages = ceil($this->total_rows / $this->ipp);
		$page = isset($_GET['page']) ? $_GET['page'] : 1 ;	
		$min = ($page - 1) * $this->ipp;
		$offset = $this->ipp;
		$this->limit = " LIMIT $min, $offset";

		$pagination = Paginate::getHtml($this->total_rows, $this->ipp, $this->ppp, $this->page_url);			
		$this->SQL = $this->SQL . $this->where. $this->order_by . $this->limit;		
		$action = $_REQUEST[$this->action];
		$this->SQL = ($action == $this->action_value)  ? $_SESSION['_sql'] : $this->SQL ;
		$_SESSION['_sql'] = $this->SQL;
		
		$this->rows = $this->db->GetAllRows($this->SQL); // get the table data here
		$this->err_msg = $this->db->GetError();
		//PR::blu($this->rows);
		if(!empty($this->rows)){
			// remove any hidden fields
			foreach((array)$this->hidden_fields as $hidden_field){
				unset($this->rows[0][$hidden_field]);
				unset($this->header[$hidden_field]);
			}
			$header = (count($this->header) > 0 )  
			? Table::cells($this->header) 
			: Table::cells(array_keys($this->rows[0]));	
			
			$trs = array();
			foreach($this->rows as $row){
				// remove any hidden fields ================================
				$fields_to_hide = array_intersect(array_keys($row), $this->hidden_fields);
				
				foreach($fields_to_hide as $field_to_hide){
					unset($row[$field_to_hide]);	
				}	
							
				// =========================================================
				$applied_func_fields = array_intersect(array_keys($row), array_keys($this->fields));
				//PR::r($applied_func_fields, $row);
				foreach((array)$applied_func_fields as $field){
					$args = "";
					if($this->fields[$field]['arguments'] && $this->fields[$field]['function'] != ""){
						$arguments = AR::join(array_merge(array("'{$row[$field]}'"), $this->fields[$field]['arguments'])); 
						$eval = '$row[$field] = ' . $this->fields[$field]['function']. '(' . $arguments. ');';	
										
						eval($eval);
					}
				}
				
				// add row css for hightlighting:
				if($this->dark_color != "" && $this->light_color != "" && $this->switch_color){
					$bg_color = Table::switchColor($this->light_color, $this->dark_color);
				}
				$tr_data = array(
				 'onmouseover="changeColor(this,\'#' . $this->highlight . '\');"', 
				 'onmouseout="changeColor(this,\'prev\');"', 
				 'id="' . $row[$this->row_index] .'"',
				  $bg_color );			
				
				$trs[] = Table::tr(Table::cells($row), AR::join($tr_data, ' ') );
				$applied_sum_fields = array_intersect(array_keys($row), $this->sum_fields);
				foreach ((array)$applied_sum_fields as $field) {
					$totals[$field] = $totals[$field] + $row[$field];
				}
				
			}
		} else {			
			$header = Table::td('No Results');	
		}
		
		if (isset($totals) && count($totals) > 0) {
			foreach ((array)array_keys($row) as $field) {
				$totals_line[] = (array_key_exists($field, $totals))  ? number_format($totals[$field], 2) : '' ;
			}
			$trs[] = Table::tr(Table::cells($totals_line), AR::join($tr_data, ' ') );
		}
		$tr = AR::join($trs, CR);
		$this->header_css = (count($header_css) > 0)  ? $header_css : array('bgcolor="#cccccc"') ;
		ob_start();
		?>

		<script type="text/javascript" 
		src="<?php echo GLOBAL_URL ?>scripts/js/global_js_functions.js" >
        </script>
        <table <?php echo AR::join($this->table_css, ' ') ?>>
            <tr <?php echo AR::join($this->header_css, ' ') ?>>
                <?php echo $header ?>
            </tr>
            <tr <?php echo AR::join($this->row_css, ' ') ?>>
                <?php echo $tr ?>
            </tr> 
            <tr <?php echo AR::join($this->row_css, ' ') ?>>
                <td nowrap="nowrap" colspan="<?php echo count($this->rows[0]) ?>"><?php echo $pagination; ?>&nbsp;<?php echo Http::glink($this->link_url, array($this->action => $this->action_value) ,'Reset') ?></td>
            </tr>   
        </table>
		<?	
		$html = ob_get_clean();
		return $html;

	} 
		

		
	/**
	*
	*
	*/
	function csv($header = array()){
		
	
	} 
		

		
	/**
	*
	*
	*/
	function set_header4($header = array()){
		
	
	} 
		

		
	/**
	*
	*
	*/
	function set_header5($header = array()){
		
	
	} 
		













	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
 }