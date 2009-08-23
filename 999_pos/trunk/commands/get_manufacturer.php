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
	 * @param Request $request
	 * @return variant
	 */
	protected function getObject($request){
		return Manufacturer::getInstance((int)$request->getProperty('id'));
	}
	
	/**
	 * Display failure in case the manufacturer does not exists.
	 */
	protected function displayFailure(){
		$back_trace = array('Inicio', 'Mantenimiento', 'Casas');
		$msg = 'Casa no existe.';
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'main_menu_operations_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_operations_html.tpl',
				'content' => 'manufacturer_menu_html.tpl', 'notify' => '1', 'type' => 'error',
				'message' => $msg), 'site_html.tpl');
	}
	
	/**
	 * Display the form for the object.
	 * @param string $key
	 * @param variant $obj
	 * @param array $backQuery
	 */
	protected function displayObject($key, $obj, $backQuery){
		$back_trace = array('Inicio', 'Mantenimiento', 'Casas');
		$id = $obj->getId();
		
		// Build the back link.
		$back_link = (is_null($backQuery)) ? 'index.php?cmd=show_manufacturer_menu' :
				'index.php?cmd=' . $backQuery['cmd'] . '&page=' . $backQuery['page'];
		// Build the foward link.
		$foward_link = 'index.php?cmd=get_manufacturer&id=' . $id;
		$foward_link .= (is_null($backQuery)) ? '' : '&last_cmd=' . $backQuery['cmd'] . '&page=' .
				$backQuery['page'];
		
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => $back_link, 'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'manufacturer_form_html.tpl', 'status' => '1', 'key' => $key, 'id' => $id,
				'name' => $obj->getName(), 'foward_link' => $foward_link, 'notify' => '0'), 'site_html.tpl');
		
	}
}
?>