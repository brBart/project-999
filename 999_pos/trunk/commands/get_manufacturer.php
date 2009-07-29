<?php
/**
 * Library containing the GetManufacturer command.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_object.php');
/**
 * Library with the manufacturer class.
 */
require_once('business/product.php');

/**
 * Displays the manufacturer form.
 * @package Command
 * @author Roberto Oliveros
 */
class GetManufacturerCommand extends GetObjectCommand{
	/**
	 * Gets the desired object.
	 * @param string $id
	 * @return variant
	 */
	protected function getObject($id){
		return Manufacturer::getInstance((int)$id);
	}
	
	/**
	 * Display failure in case the manufacturer does not exists.
	 */
	protected function displayFailure(){
		$back_trace = array('Inicio', 'Mantenimiento', 'Casas');
		$msg = 'Casa no existe.';
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'main_menu_operations_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_operations_html.tpl',
				'content' => 'manufacturers_menu_html.tpl', 'notify' => '1', 'type' => 'error',
				'message' => $msg), 'site_html.tpl');
	}
	
	/**
	 * Display the form for the object.
	 * @param string $key
	 * @param variant $obj
	 */
	protected function displayObject($key, $obj){
		$back_trace = array('Inicio', 'Mantenimiento', 'Casas');
		$id = $obj->getId();
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => 'index.php?cmd=show_manufacturers_menu', 'back_trace' => $back_trace,
				'second_menu' => 'blank.tpl', 'content' => 'manufacturer_form_html.tpl',
				'status' => '1', 'key' => $key, 'id' => $id, 'name' => $obj->getName(),
				'on_cancel' => 'index.php?get_manufacturer&id=' . $id, 'notify' => '0'), 'site_html.tpl');
	}
}
?>