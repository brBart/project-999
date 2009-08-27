<?php
/**
 * Library containing the GetProductBySupplier command.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_product.php');

/**
 * Displays the product form.
 * @package Command
 * @author Roberto Oliveros
 */
class GetProductBySupplierCommand extends GetProductCommand{
	/**
	 * Gets the desired object.
	 * @return variant
	 */
	protected function getObject(){
		$supplier_id = $this->_mRequest->getProperty('supplier_id');
		$product_sku = $this->_mRequest->getProperty('product_sku');
		
		if($supplier_id == '')
			throw new Exception('Seleccione un proveedor.');
		if($product_sku == '')
			throw new Exception('C&oacute;digo segun proveedor inv&aacute;lido. ' .
					'Valor no puede ser vac&iacute;o.');
		
		$supplier = Supplier::getInstance((int)$supplier_id);
		if(is_null($supplier))
			throw new Exception('Proveedor no existe.');
			
		$id = Product::getProductIdBySupplier($supplier, $product_sku);
		if($id > 0){
			$product = Product::getInstance($id);
			return $product;
		}
		else
			throw new Exception('Product no existe.');
	}
	
	/**
	 * Display failure in case the manufacturer does not exists.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio', 'Mantenimiento', 'Productos');
		
		$supplier_id = $this->_mRequest->getProperty('supplier_id');
		$product_sku = $this->_mRequest->getProperty('product_sku');
		
		// For displaying the first blank item.
		$list = array(array());
		$list = array_merge($list, SupplierList::getList($pages, $items, 0));
		
		Page::display(array('module_title' => OPERATIONS_TITLE, 'main_menu' => 'main_menu_operations_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_operations_html.tpl',
				'content' => 'product_menu_html.tpl', 'supplier_list' => $list, 'notify' => '1',
				'type' => 'error', 'message' => $msg, 'supplier_id' => $supplier_id,
				'product_sku' => $product_sku), 'site_html.tpl');
	}
}
?>