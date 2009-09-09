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
 * Displays the manufacturer form in idle mode.
 * @package Command
 * @author Roberto Oliveros
 */
class GetManufacturerCommand extends GetObjectCommand{
	/**
	 * Gets the desired object.
	 * @return variant
	 */
	protected function getObject(){
		$manufacturer = Manufacturer::getInstance((int)$this->_mRequest->getProperty('id'));
		if(!is_null($manufacturer))
			return $manufacturer;
		else
			throw new Exception('Casa no existe.');
	}
	
	/**
	 * Display failure in case the object does not exists or an error occurs.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio', 'Mantenimiento', 'Casas');
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