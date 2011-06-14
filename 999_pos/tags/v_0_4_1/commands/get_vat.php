<?php
/**
 * Library containing the GetVatCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_unique_object.php');
/**
 * Library with the company class.
 */
require_once('business/document.php');

/**
 * Displays the V.A.T. form in idle mode.
 * @package Command
 * @author Roberto Oliveros
 */
class GetVatCommand extends GetUniqueObjectCommand{
	/**
	 * Gets the desired object.
	 * @return variant
	 */
	protected function getObject(){
		return Vat::getInstance();
	}
	
	/**
	 * Display failure in case an error occurs.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio', 'Mantenimiento', 'I.V.A.');
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'main_menu_pos_admin_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_pos_admin_html.tpl',
				'content' => 'none', 'notify' => '1', 'type' => 'error',
				'message' => $msg), 'site_html.tpl');
	}
	
	/**
	 * Display the form for the object.
	 * @param string $key
	 * @param variant $obj
	 */
	protected function displayObject($key, $obj){
		$back_trace = array('Inicio', 'Mantenimiento', 'I.V.A.');
		$back_link = 'index.php?cmd=show_maintenance_menu_pos_admin';
		$forward_link = 'index.php?cmd=get_vat';
		
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => $back_link, 'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'vat_form_html.tpl', 'key' => $key, 'percentage' => $obj->getPercentage(),
				'forward_link' => $forward_link, 'edit_cmd' => 'edit_vat'), 'site_html.tpl');
	}
}
?>