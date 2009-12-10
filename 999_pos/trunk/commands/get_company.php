<?php
/**
 * Library containing the GetCompanyCommand class.
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
require_once('business/various.php');

/**
 * Displays the company form in idle mode.
 * @package Command
 * @author Roberto Oliveros
 */
class GetCompanyCommand extends GetUniqueObjectCommand{
	/**
	 * Gets the desired object.
	 * @return variant
	 */
	protected function getObject(){
		return Company::getInstance();
	}
	
	/**
	 * Display failure in case the object does not exists or an error occurs.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio', 'Mantenimiento', 'Empresa');
		Page::display(array('module_title' => ADMIN_TITLE, 'main_menu' => 'main_menu_admin_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_admin_html.tpl',
				'content' => 'none', 'notify' => '1', 'type' => 'error',
				'message' => $msg), 'site_html.tpl');
	}
	
	/**
	 * Display the form for the object.
	 * @param string $key
	 * @param variant $obj
	 */
	protected function displayObject($key, $obj){
		$back_trace = array('Inicio', 'Mantenimiento', 'Empresa');
		$back_link = 'index.php?cmd=show_maintenance_menu_admin';
		$foward_link = 'index.php?cmd=get_company';
		
		Page::display(array('module_title' => ADMIN_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => $back_link, 'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'company_form_html.tpl', 'key' => $key, 'name' => $obj->getName(),
				'nit' => $obj->getNit(), 'foward_link' => $foward_link, 'edit_cmd' => 'edit_company'),
				'site_html.tpl');
	}
}
?>