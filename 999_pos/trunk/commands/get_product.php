<?php
/**
 * Library containing the GetProductCommand class.
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
 * For creating the select options.
 */
require_once('business/list.php');

/**
 * Defines common functionality for the get product derived classes. Displays the product form in idle mode.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class GetProductCommand extends GetObjectCommand{
	/**
	 * Display the form for the object.
	 * @param string $key
	 * @param variant $obj
	 * @param array $backQuery
	 */
	protected function displayObject($key, $obj, $backQuery){
		$back_trace = array('Inicio', 'Mantenimiento', 'Productos');
		$id = $obj->getId();
		
		// Build the back link.
		$back_link = (is_null($backQuery)) ? 'index.php?cmd=show_product_menu' :
				'index.php?cmd=' . $backQuery['cmd'] . '&page=' . $backQuery['page'];
		// Build the foward link.
		$foward_link = 'index.php?cmd=get_product_by_id&id=' . $id;
		$foward_link .= (is_null($backQuery)) ? '' : '&last_cmd=' . $backQuery['cmd'] . '&page=' .
				$backQuery['page'];

		// Get the lists for the select options.
		$empty_item = array(array());
		$manufacturer_list = array_merge($empty_item, ManufacturerList::getList($pages, $items, 0));
		$um_list = array_merge($empty_item, UnitOfMeasureList::getList($pages, $items, 0));
		$supplier_list = array_merge($empty_item, SupplierList::getList($pages, $items, 0));
		
		// Get objects contain in the product.
		$manufacturer = $obj->getManufacturer();
		$um = $obj->getUnitOfMeasure();
		
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'back_link.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'product_form_html.tpl', 'status' => '1', 'key' => $key,
				'back_link' => $back_link, 'foward_link' => $foward_link,
				'manufacturer_list' => $manufacturer_list, 'um_list' => $um_list,
				'supplier_list' => $supplier_list, 'id' => $id, 'name' => $obj->getName(),
				'bar_code' => $obj->getBarCode(), 'packaging' => $obj->getPackaging(),
				'description' => $obj->getDescription(), 'manufacturer_id' => $manufacturer->getId(),
				'um_id' => $um->getId(), 'price' => $obj->getPrice(),
				'deactivated' => (int)$obj->isDeactivated(), 'quantity' => Inventory::getQuantity($obj),
				'available' => Inventory::getAvailable($obj)), 'site_html.tpl');
	}
}
?>