<?php
/**
 * Library containing the GetCorrelativeCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_object.php');
/**
 * Library with the correlative class.
 */
require_once('business/document.php');

/**
 * Displays the branch form in idle mode.
 * @package Command
 * @author Roberto Oliveros
 */
class GetCorrelativeCommand extends GetObjectCommand{
	/**
	 * Gets the desired object.
	 * @return variant
	 */
	protected function getObject(){
		$correlative = Correlative::getInstance($this->_mRequest->getProperty('id'));
		if(!is_null($correlative))
			return $correlative;
		else
			throw new Exception('Correlativo no existe.');
	}
	
	/**
	 * Display failure in case the object does not exists or an error occurs.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio', 'Mantenimiento', 'Correlativos');
		Page::display(array('module_title' => ADMIN_TITLE, 'main_menu' => 'main_menu_admin_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_admin_html.tpl',
				'content' => 'object_menu_html.tpl', 'notify' => '1', 'type' => 'error',
				'message' => $msg, 'create_link' => 'index.php?cmd=create_correlative',
				'show_list_link' => 'index.php?cmd=show_correlative_list&page=1'), 'site_html.tpl');
	}
	
	/**
	 * Display the form for the object.
	 * @param string $key
	 * @param variant $obj
	 * @param array $backQuery
	 */
	protected function displayObject($key, $obj, $backQuery){
		$back_trace = array('Inicio', 'Mantenimiento', 'Correlativos');
		
		// Build the back link.
		$back_link = (is_null($backQuery)) ? 'index.php?cmd=show_correlative_menu' :
				'index.php?cmd=' . $backQuery['cmd'] . '&page=' . $backQuery['page'];
		
		Page::display(array('module_title' => ADMIN_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => $back_link, 'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'correlative_form_html.tpl', 'status' => '1', 'key' => $key,
				'serial_number' => $obj->getSerialNumber(), 'is_default' => $obj->isDefault(),
				'resolution_number' => $obj->getResolutionNumber(),
				'resolution_date' => $obj->getResolutionDate(), 'initial_number' => $obj->getInitialNumber(),
				'final_number' => $obj->getFinalNumber(), 'actual_number' => $obj->getCurrentNumber(),
				'delete_cmd' => 'delete_correlative'), 'site_html.tpl');
	}
}
?>