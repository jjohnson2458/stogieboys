<?php
define('CONFIG_FILE','/home/mkephart/config/live.php');
require_once(CONFIG_FILE);
define('DB_SERVER',DB_HOST);
define('DB_SERVER_USERNAME',DB_USER);
define('DB_SERVER_PASSWORD',DB_PASS);
define('DB_DATABASE',DB_NAME);
require_once(GLOBAL_CLASSES_DIR . 'class.base.php');
require_once(GLOBAL_CLASSES_DIR . 'query_factory.php');
require_once(GLOBAL_CLASSES_DIR . 'deals_admin.php');
require_once(GLOBAL_CLASSES_DIR . 'table_builder_class.php');
?>
