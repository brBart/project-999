<?php
/**
 * Library containing the add supplier product command.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('presentation/command.php');
/**
 * For displaying the results.
 */
require_once('presentation/page.php');
/**
 * For accessing the supplier data.
 */
require_once('business/agent.php');

/**
 * Defines functionality for adding a supplier to a product.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class AddSupplierProductCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$id = $request->getProperty('supplier_id');
		// Check if the user selected a supplier.
		if($id == ''){
			$msg = 'Seleccione un proveedor.';
			Page::display(array('success' => '0', 'element_id' => 'supplier_id', 'message' => $msg),
					'validate_xml.tpl');
			return;
		}
		
		$supplier = Supplier::getInstance((int)$id);
		// If id is not valid.
		if(is_null($supplier)){
			$msg = 'Proveedor no existe.';
			Page::display(array('message' => $msg), 'error_xml.tpl');
		}
		else{
			$product_sku = $request->getProperty('product_sku');
			
			try{
				$detail = new ProductSupplier($supplier, $product_sku);
			} catch(ValidateException $e){
				$msg = $e->getMessage();
				$element_id = $e->getProperty();
				Page::display(array('success' => '0', 'element_id' => $element_id, 'message' => $msg),
						'validate_xml.tpl');
				return;
			} catch(Exception $e){
				$msg = $e->getMessage();
				Page::display(array('message' => $msg), 'error_xml.tpl');
				return;
			}
			
			$product = $helper->getObject((int)$request->getProperty('key'));
			
		}
	}
}
?>