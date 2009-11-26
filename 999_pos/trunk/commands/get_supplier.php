<?php
/**
 * Library containing the GetSupplierCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_object.php');
/**
 * Library with the supplier class.
 */
require_once('business/agent.php');

/**
 * Displays the supplier form in idle mode.
 * @package Command
 * @author Roberto Oliveros
 */
class GetSupplierCommand extends GetObjectCommand{
	/**
	 * Gets the desired object.
	 * @return variant
	 */
	protected function getObject(){
		$supplier = Supplier::getInstance((int)$this->_mRequest->getProperty('id'));
		if(!is_null($supplier))
			return $supplier;
		else
			throw new Exception('Proveedor no existe.');
	}
	
	/**
	 * Display failure in case the object does not exists or an error occurs.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio', 'Mantenimiento', 'Proveedores');
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'main_menu_operations_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_operations_html.tpl',
				'content' => 'object_menu_html.tpl', 'notify' => '1', 'type' => 'error',
				'message' => $msg, 'create_link' => 'index.php?cmd=create_supplier',
				'show_list_link' => 'index.php?cmd=show_supplier_list&page=1'), 'site_html.tpl');
	}
	
	/**
	 * Display the form for the object.
	 * @param string $key
	 * @param variant $obj
	 * @param array $backQuery
	 */
	protected function displayObject($key, $obj, $backQuery){
		$back_trace = array('Inicio', 'Mantenimiento', 'Proveedores');
		$id = $obj->getId();
		
		// Build the back link.
		$back_link = (is_null($backQuery)) ? 'index.php?cmd=show_supplier_menu' :
				'index.php?cmd=' . $backQuery['cmd'] . '&page=' . $backQuery['page'];
		// Build the foward link.
		$foward_link = 'index.php?cmd=get_supplier';
		$foward_link .= (is_null($backQuery)) ? '' : '&last_cmd=' . $backQuery['cmd'] . '&page=' .
				$backQuery['page'];
		
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => $back_link, 'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'supplier_form_html.tpl', 'status' => '1', 'key' => $key, 'id' => $id,
				'name' => $obj->getName(), 'nit' => $obj->getNit(), 'telephone' => $obj->getTelephone(),
				'address' => $obj->getAddress(), 'email' => $obj->getEmail(), 'contact' => $obj->getContact(),
				'foward_link' => $foward_link, 'edit_cmd' => 'edit_supplier',
				'delete_cmd' => 'delete_supplier'), 'site_html.tpl');
	}
}
?>