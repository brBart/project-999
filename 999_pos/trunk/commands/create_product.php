<?php
/**
 * Library containing the CreateProduct command.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/create_object.php');
/**
 * Library with the product class.
 */
require_once('business/product.php');
/**
 * For creating the select options.
 */
require_once('business/list.php');

/**
 * Displays the product form.
 * @package Command
 * @author Roberto Oliveros
 */
class CreateProductCommand extends CreateObjectCommand{
	/**
	 * Tests if the user has the right to create an object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'product', 'write');
	}
	
	/**
	 * Display failure in case the user doesn't have rights.
	 */
	protected function displayFailure(){
		$back_trace = array('Inicio', 'Mantenimiento', 'Productos');
		$msg = 'Usuario no cuenta con los suficientes privilegios.';
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'main_menu_operations_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_operations_html.tpl',
				'content' => 'product_menu_html.tpl', 'notify' => '1', 'type' => 'error',
				'message' => $msg), 'site_html.tpl');
	}
	
	/**
	 * Creates the desired object.
	 * @return variant
	 */
	protected function createObject(){
		return new Product();
	}
	
	/**
	 * Display the form for creating the object.
	 * @param string $key
	 * @param variant $obj
	 */
	protected function displayObject($key, $obj){
		$back_trace = array('Inicio', 'Mantenimiento', 'Productos');
		
		// Get the lists for the select options.
		$empty_item = array(array());
		$manufacturer_list = array_merge($empty_item, ManufacturerList::getList($pages, $items, 0));
		$um_list = array_merge($empty_item, UnitOfMeasureList::getList($pages, $items, 0));
		$supplier_list = array_merge($empty_item, SupplierList::getList($pages, $items, 0));
		
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'blank.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'product_form_html.tpl', 'status' => '0', 'key' => $key,
				'back_link' => 'index.php?cmd=show_product_menu',
				'foward_link' => 'index.php?cmd=get_product_by_id', 'manufacturer_list' => $manufacturer_list,
				'um_list' => $um_list, 'supplier_list' => $supplier_list), 'site_html.tpl');
	}
}
?>