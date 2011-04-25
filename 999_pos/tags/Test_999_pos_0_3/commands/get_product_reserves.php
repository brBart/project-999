<?php
/**
 * Library containing the GetProductReservesCommand class.
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
 * Defines functionality for obtaining the product's reserves.
 * @package Command
 * @author Roberto Oliveros
 */
class GetProductReservesCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$product = $helper->getObject((int)$request->getProperty('key'));
		$product_reserves = ReserveList::getList($product);
		$page_items = count($product_reserves);
		$page = ($page_items > 0) ? 1 : 0;
		
		Page::display(array('reserves' => $product_reserves, 'page_items' => $page_items,
				'page' => $page, 'quantity' => $quantity, 'available' => $available),
				'product_reserves_xml.tpl');
	}
}
?>