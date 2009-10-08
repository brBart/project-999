<?php
/**
 * Library containing the GetProductBonusCommand class.
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
 * For obtaining the list of bonus.
 */
require_once('business/product.php');

/**
 * Defines functionality for obtaining a product's bonus.
 * @package Command
 * @author Roberto Oliveros
 */
class GetProductBonusCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$product = $helper->getObject((int)$request->getProperty('key'));
		$product_bonus = ProductBonusList::getList($product);
		$page_items = count($product_bonus);
		$page = ($page_bonus > 0) ? 1 : 0;
		
		Page::display(array('bonus' => $product_bonus, 'page_items' => $page_items,
				'page' => $page), 'product_bonus_xml.tpl');
	}
}
?>