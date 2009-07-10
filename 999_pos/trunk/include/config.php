<?php
define('ITEMS_PER_PAGE', 4);

define('DB_PERSISTENCY', 'true');
define('DB_SERVER', 'localhost');
define('DB_USERNAME', '999_user');
define('DB_PASSWORD', '999_user');
define('DB_DATABASE', '999_store');
define('PDO_DSN', 'mysql:host=' . DB_SERVER . ';dbname=' . DB_DATABASE);

define('SITE_ROOT', dirname(dirname(__FILE__)));
define('BACKUP_DIR', SITE_ROOT . '/backup/');
define('COMMANDS_DIR', SITE_ROOT . '/commands/');
define('SMARTY_DIR', 'C:\\xampp\\php\\Smarty-2.6.26\\libs\\');
define('TEMPLATE_DIR', SITE_ROOT . '/templates/');
define('COMPILE_DIR', SITE_ROOT . '/templates_c/');
define('CONFIG_DIR', SITE_ROOT . '/include/configs');
?>