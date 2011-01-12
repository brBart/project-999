<?php
/**
 * Library containing the GetProductBalanceCommand class.
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
 * Defines functionality for obtaining the product's balance.
 * @package Command
 * @author Roberto Oliveros
 */
class GetProductBalanceCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$product = $helper->getObject((int)$request->getProperty('key'));
		
		Page::display(array('quantity' => Inventory::getQuantity($product),
				'available' => Inventory::getAvailable($product)), 'product_balance_xml.tpl');
	}
}
?>