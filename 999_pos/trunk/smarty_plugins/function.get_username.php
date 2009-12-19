<?php
/**
 * Smarty plugin for getting the actual username.
 * @package 999_pos
 * @author Roberto Oliveros
 */

/**
 * Smarty plugin for getting the actual username.
 */
function smarty_function_get_username($params, &$smarty)
{
    $helper = ActiveSession::getHelper();
    return $helper->getUser()->getUserName();
}
?>