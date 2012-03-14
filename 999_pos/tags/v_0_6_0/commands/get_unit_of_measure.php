<?php
/**
 * Library containing the GetUnitOfMeasureCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_object.php');
/**
 * Library with the unit of measure class.
 */
require_once('business/product.php');

/**
 * Displays the unit of measure form in idle mode.
 * @package Command
 * @author Roberto Oliveros
 */
class GetUnitOfMeasureCommand extends GetObjectCommand{
	/**
	 * Gets the desired object.
	 * @return variant
	 */
	protected function getObject(){
		$um = UnitOfMeasure::getInstance((int)$this->_mRequest->getProperty('id'));
		if(!is_null($um))
			return $um;
		else
			throw new Exception('Unidad de medida no existe.');
	}
	
	/**
	 * Display failure in case the object does not exists or an error occurs.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio', 'Mantenimiento', 'Unidades de Medida');
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'main_menu_inventory_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_inventory_html.tpl',
				'content' => 'object_menu_html.tpl', 'notify' => '1', 'type' => 'error',
				'message' => $msg, 'create_link' => 'index.php?cmd=create_unit_of_measure',
				'show_list_link' => 'index.php?cmd=show_unit_of_measure_list&page=1'), 'site_html.tpl');
	}
	
	/**
	 * Display the form for the object.
	 * @param string $key
	 * @param variant $obj
	 * @param array $backQuery
	 */
	protected function displayObject($key, $obj, $backQuery){
		$back_trace = array('Inicio', 'Mantenimiento', 'Unidades de Medida');
		$id = $obj->getId();
		
		// Build the back link.
		$back_link = (is_null($backQuery)) ? 'index.php?cmd=show_unit_of_measure_menu' :
				'index.php?cmd=' . $backQuery['cmd'] . '&page=' . $backQuery['page'];
		// Build the forward link.
		$forward_link = 'index.php?cmd=get_unit_of_measure';
		$forward_link .= (is_null($backQuery)) ? '' : '&last_cmd=' . $backQuery['cmd'] . '&page=' .
				$backQuery['page'];
		
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => $back_link, 'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'identifier_form_html.tpl', 'status' => '1', 'key' => $key, 'id' => $id,
				'name' => $obj->getName(), 'forward_link' => $forward_link, 'edit_cmd' => 'edit_unit_of_measure',
				'delete_cmd' => 'delete_unit_of_measure'), 'site_html.tpl');
	}
}
?>