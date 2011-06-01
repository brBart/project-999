<?php
/**
 * Smarty plugin for getting the system's help url.
 * @package 999_pos
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