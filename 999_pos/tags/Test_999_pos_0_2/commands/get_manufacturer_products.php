<?php
/**
 * Library containing the GetManufacturerProductsCommand class.
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
 * Defines functionality for obtaining the manufacturer's products.
 * @package Command
 * @author Roberto Oliveros
 */
class GetManufacturerProductsCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$manufacturer = $helper->getObject((int)$request->getProperty('key'));
		$products = ManufacturerProductList::getList($manufacturer);
		$page_items = count($products);
		$page = ($page_items > 0) ? 1 : 0;
		
		Page::display(array('products' => $products, 'page_items' => $page_items, 'page' => $page),
				'manufacturer_product_list_xml.tpl');
	}
}
?>