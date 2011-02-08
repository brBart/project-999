<?php
/**
 * Library containing the GetProductLotsCommand class.
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
 * Defines functionality for obtaining the product's lots.
 * @package Command
 * @author Roberto Oliveros
 */
class GetProductLotsCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$product = $helper->getObject((int)$request->getProperty('key'));
		$product_lots = Inventory::showLots($product, $quantity, $available);
		$page_items = count($product_lots);
		$page = ($page_items > 0) ? 1 : 0;
		
		Page::display(array('lots' => $product_lots, 'page_items' => $page_items,
				'page' => $page, 'quantity' => $quantity, 'available' => $available),
				'product_lots_xml.tpl');
	}
}
?>