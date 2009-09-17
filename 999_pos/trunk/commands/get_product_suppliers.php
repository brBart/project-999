<?php
/**
 * Library containing the GetProductSuppliers class command.
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
 * Defines functionality for obtaining a product's suppliers.
 * @package Command
 * @author Roberto Oliveros
 */
class GetProductSuppliersCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$product = $helper->getObject((int)$request->getProperty('key'));
		$product_suppliers = $product->showProductSuppliers();
		$page_items = count($product_suppliers);
		$page = ($page_items > 0) ? 1 : 0;
		
		Page::display(array('suppliers' => $product_suppliers, 'page_items' => $page_items,
				'page' => $page), 'product_suppliers_xml.tpl');
	}
}
?>