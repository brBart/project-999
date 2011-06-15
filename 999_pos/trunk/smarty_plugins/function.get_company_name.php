<?php
/**
 * Smarty plugin for getting the company's name.
 * @package Pos
 * @author Roberto Oliveros
 */

/**
 * Include the company library.
 */
require_once('business/various.php');

/**
 * Smarty plugin for getting the company's name.
 */
function smarty_function_get_company_name($params, &$smarty)
{
	return Company::getInstance()->getName();
}
?>