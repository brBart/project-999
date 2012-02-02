<?php
define('ITEMS_PER_PAGE', 4);
define('PRODUCTS_PER_PAGE', 4);
define('MAX_QUANTITY', 9999);

define('MYSQL_DIR', '@mysql_dir@');
define('DB_PERSISTENCY', 'true');
define('DB_SERVER', '@db_server@');
define('DB_USERNAME', '@db_user@');
define('DB_PASSWORD', '@db_password@');
define('DB_DATABASE', '@db_database@');
define('PDO_DSN', 'mysql:host=' . DB_SERVER . ';dbname=' . DB_DATABASE);

define('CORRELATIVE_VALID_DAYS', 10);

define('SITE_ROOT', dirname(dirname(__FILE__)));
define('BACKUP_DIR_NAME', '/backup/');
define('BACKUP_DIR', SITE_ROOT . BACKUP_DIR_NAME);
define('SALES_LEDGER_DIR_NAME', '/sales_ledger/');
define('SALES_LEDGER_DIR', SITE_ROOT . SALES_LEDGER_DIR_NAME);
define('COMMANDS_DIR', SITE_ROOT . '/commands/');

define('LIBS_999', '@libs_dir@');
define('PEAR_DIR', '@pear_dir@');
set_include_path(get_include_path() . ';' . LIBS_999 . ';' . SITE_ROOT . ';' . PEAR_DIR);
?>