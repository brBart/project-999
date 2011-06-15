<?php
/**
 * Smarty plugin for getting the system's help url.
 * @package Pos
 * @author Roberto Oliveros
 */

/**
 * Smarty plugin for getting the system's help url.
 */
function smarty_function_get_help_url($params, &$smarty)
{
	return HELP_URL;
}
?>