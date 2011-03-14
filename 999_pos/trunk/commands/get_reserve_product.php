<?php
/**
 * Library containing the GetReserveProductCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_object.php');
/**
 * Library with the product class.
 */
require_once('business/product.php');

/**
 * Defines common functionality for the get reserve product derived classes. Displays the product reserve form.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class GetReserveProductCommand extends GetObjectCommand{
	/**
	 * Display the form for the object.
	 * @param string $key
	 * @param variant $obj
	 * @param array $backQuery
	 */
	protected function displayObject($key, $obj, $backQuery){
		$back_trace = array('Inicio', 'Mantenimiento', 'Reservados');
		$id = $obj->getId();
		
		Page::display(array('module_title' => ADMIN_TITLE, 'main_menu' => 'back_link.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'none', 'content' => 'reserve_form_html.tpl',
				'key' => $key, 'back_link' => 'index.php?cmd=show_reserve_menu', 'id' => $id,
				'name' => $obj->getName(), 'bar_code' => $obj->getBarCode(),
				'price' => $obj->getPrice(), 'quantity' => Inventory::getQuantity($obj),
				'available' => Inventory::getAvailable($obj)), 'site_html.tpl');
	}
}
?>