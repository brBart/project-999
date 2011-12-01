<?php
define('ITEMS_PER_PAGE', 4);
define('PRODUCTS_PER_PAGE', 4);

define('MYSQL_DIR', 'C:/xampp/mysql/bin/');
define('DB_PERSISTENCY', 'true');
define('DB_SERVER', '127.0.0.1');
define('DB_USERNAME', '999_user');
define('DB_PASSWORD', '999_user');
define('DB_DATABASE', '999_store');
define('PDO_DSN', 'mysql:host=' . DB_SERVER . ';dbname=' . DB_DATABASE);

define('CORRELATIVE_VALID_DAYS', 10);

define('SITE_ROOT', dirname(dirname(__FILE__)));
define('BACKUP_DIR_NAME', '/backup/');
define('BACKUP_DIR', SITE_ROOT . BACKUP_DIR_NAME);
define('SALES_LEDGER_DIR_NAME', '/sales_ledger/');
define('SALES_LEDGER_DIR', SITE_ROOT . SALES_LEDGER_DIR_NAME);
define('COMMANDS_DIR', SITE_ROOT . '/commands/');
?>