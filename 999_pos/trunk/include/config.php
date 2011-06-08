<?php
/**
 * PHP file with all the configuration variables for the system to run properly. The database user and password
 * constants are ignored in this documentation for security reasons.
 * @package 999_pos
 * @author Roberto Oliveros
 */

/**
 * Web site path.
 */
define('SITE_ROOT', dirname(dirname(__FILE__)));
/**
 * 999 libraries path.
 */
define('LIBS_999', 'C:/Users/pc/999_project/999_middle/');
/**
 * Backup directory name.
 */ 
define('BACKUP_DIR_NAME', '/backup/');
/**
 * Backup directory path.
 */ 
define('BACKUP_DIR', SITE_ROOT . BACKUP_DIR_NAME);
/**
 * Sales ledger directory name.
 */ 
define('SALES_LEDGER_DIR_NAME', '/sales_ledger/');
/**
 * Sales ledger directory path.
 */ 
define('SALES_LEDGER_DIR', SITE_ROOT . SALES_LEDGER_DIR_NAME);
/** 
 * Command directory (where all the commands reside) path.
 */
define('COMMANDS_DIR', SITE_ROOT . '/commands/');
/**
 * Smarty paths.
 */ 
define('SMARTY_DIR', 'C:/xampp/php/Smarty-2.6.26/libs/');
/**
 * Smarty paths.
 */
define('TEMPLATE_DIR', SITE_ROOT . '/templates/');
/**
 * Smarty paths.
 */
define('COMPILE_DIR', SITE_ROOT . '/templates_c/');
/**
 * Smarty paths.
 */
define('CONFIG_DIR', SITE_ROOT . '/include/configs');
/**
 * Smarty paths.
 */
define('PLUGINS_DIR', SITE_ROOT . '/smarty_plugins');
/**
 * Help dir url.
 */
define('HELP_URL', 'http://localhost/999_help/');
/**
 * Indicate where all the system libraries reside.
 */
set_include_path(get_include_path() . ';' . LIBS_999 . ';' . SITE_ROOT);
/** 
 * How many items per page will be show for the documents.
 */
define('ITEMS_PER_PAGE', 10);
/** 
 * How many products per page will be show for the product list.
 */
define('PRODUCTS_PER_PAGE', 50);
/**
 * MySQL path.
 */ 
define('MYSQL_DIR', 'C:/xampp/mysql/bin/');
/**
 * Database configuration.
 */ 
define('DB_PERSISTENCY', 'true');
/**
 * Database configuration.
 */
define('DB_SERVER', '127.0.0.1');
/**
 * @ignore
 */
define('DB_USERNAME', '999_user');
/**
 * @ignore
 */
define('DB_PASSWORD', '999_user');
/**
 * Database configuration.
 */
define('DB_DATABASE', '999_store');
/**
 * Database configuration.
 */
define('PDO_DSN', 'mysql:host=' . DB_SERVER . ';dbname=' . DB_DATABASE);
/**
 * Correlative limit percentage for create a usage warning.
 */
define('CORRELATIVE_CONSUME_WARNING', 75);
/**
 * Title of the inventory module.
 */
define('INVENTORY_TITLE', 'Inventario');
/**
 * Title of the admin module.
 */
define('ADMIN_TITLE', 'Administraci&oacute;n');
/**
 * Title of the POS module.
 */
define('POS_TITLE', 'POS');
/**
 * Title of the POS Admin module.
 */
define('POS_ADMIN_TITLE', 'POS Admin');
?>