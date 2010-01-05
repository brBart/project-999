<?php
/**
 * Library containing the GetSupplierProductsCommand class.
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
 * For obtaining the list of products.
 */
require_once('business/product.php');

/**
 * Defines functionality for obtaining the supplier's products.
 * @package Command
 * @author Roberto Oliveros
 */
class GetSupplierProductsCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$supplier = $helper->getObject((int)$request->getProperty('key'));
		$products = SupplierProductList::getList($supplier);
		$page_items = count($products);
		$page = ($page_items > 0) ? 1 : 0;
		
		Page::display(array('products' => $products, 'page_items' => $page_items, 'page' => $page),
				'product_list_xml.tpl');
	}
}
?>