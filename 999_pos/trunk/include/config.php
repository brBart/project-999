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
define('LIBS_999', 'C:\\xampp\\htdocs\\sistema999\\999_project\\999_web\\');
/**
 * Backup directory path.
 */ 
define('BACKUP_DIR', SITE_ROOT . '/backup/');
/** 
 * Command directory (where all the commands reside) path.
 */
define('COMMANDS_DIR', SITE_ROOT . '/commands/');
/**
 * Smarty paths.
 */ 
define('SMARTY_DIR', 'C:\\xampp\\php\\Smarty-2.6.26\\libs\\');
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
 * Indicate where all the system libraries reside.
 */
set_include_path(get_include_path() . ';' . LIBS_999 . ';' . SITE_ROOT);
/** 
 * How many items per page will be show for the documents.
 */
define('ITEMS_PER_PAGE', 10);
/**
 * Database configuration.
 */ 
define('DB_PERSISTENCY', 'true');
/**
 * Database configuration.
 */
define('DB_SERVER', 'localhost');
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
 * Title of the operations module.
 */
define('OPERATIONS_TITLE', 'Operaciones');
?>