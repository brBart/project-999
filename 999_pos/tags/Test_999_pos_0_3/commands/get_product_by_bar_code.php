<?php
/**
 * Library containing the GetProductByBarCodeCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_product.php');

/**
 * Defines functionality for obtaining a product object or displaying failure.
 * @package Command
 * @author Roberto Oliveros
 */
class GetProductByBarCodeCommand extends GetProductCommand{
	/**
	 * Gets the desired object.
	 * @return variant
	 */
	protected function getObject(){
		$bar_code = $this->_mRequest->getProperty('bar_code');
		$include_deactivated = (boolean)$this->_mRequest->getProperty('include_deactivated');
		
		if($bar_code != ''){
			$id = Product::getProductIdByBarCode($bar_code, $include_deactivated);
			if($id > 0)
				return Product::getInstance($id);
			else
				throw new Exception('Product no existe.');
		}
		else
			throw new Exception('C&oacute;digo de barra inv&aacute;lido. Valor no puede ser vac&iacute;o.');
	}
	
	/**
	 * Display failure in case the product does not exists.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio', 'Mantenimiento', 'Productos');
		
		$bar_code = $this->_mRequest->getProperty('bar_code');
		
		// For displaying the first blank item.
		$list = array(array());
		$list = array_merge($list, SupplierList::getList($pages, $items, 0));
		
		Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'main_menu_inventory_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_inventory_html.tpl',
				'content' => 'product_menu_html.tpl', 'supplier_list' => $list, 'notify' => '1',
				'type' => 'error', 'message' => $msg, 'bar_code' => $bar_code), 'site_html.tpl');
	}
}
?>