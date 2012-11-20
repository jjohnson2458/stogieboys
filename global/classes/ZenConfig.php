<?php
/**
*
*
*/

class ZenConfig
{
	public static function load()
	{
		if (!defined('IS_ADMIN_FLAG')) {
			// load zen cart configuration first:
			if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/includes/configure.php')) {
				require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/configure.php');
			}

			if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/includes/database_tables.php')) {
				require_once($_SERVER['DOCUMENT_ROOT'] . '/includes/database_tables.php');
			}
			
			if (defined('DB_DATABASE') && defined('TABLE_CONFIGURATION') ) {
				$_db = new MySQL(DB_DATABASE, DB_SERVER, DB_SERVER_USERNAME, DB_SERVER_PASSWORD);
				$configuration_sql = "SELECT 
									configuration_key as `key`,
									configuration_value as `value`
									FROM " . TABLE_CONFIGURATION;
				$configurations = $_db->Query($configuration_sql);				
				while ($configuration = $_db->GetRow($configurations)) {
						Define::set($configuration['key'], $configuration['value']);
				}

				$configuration_sql = "SELECT 
									configuration_key as `key`,
									configuration_value as `value`
									FROM " . TABLE_PRODUCT_TYPE_LAYOUT;					
				$configurations = $_db->Query($configuration_sql);				
				while ($configuration = $_db->GetRow($configurations)) {
						Define::set($configuration['key'], $configuration['value']);
				}
				
			}



		} 
	}
}