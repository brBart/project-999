<?php
/**
 * Smarty plugin for getting the company's warehouse name.
 * @package Pos
 * @author Roberto Oliveros
 */

/**
 * Include the company library.
 */
require_once('business/various.php');

/**
 * Smarty plugin for getting the company's warehouse name.
 */
function smarty_function_get_warehouse_name($params, &$smarty)
{
	return Company::getInstance()->getWarehouseName();
}
?>