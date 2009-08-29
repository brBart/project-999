<?php
/**
 * Library containing the get object base class command.
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
 * For the ProductSearch class.
 */
require_once('business/product.php');

/**
 * Defines functionality for searching a product in the database.
 * @package Command
 * @author Roberto Oliveros
 */
class SearchProductCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$list = ProductSearch::search($request->getProperty('search_str'));
		Page::display(array('list' => $list), 'search_product_list_xml.tpl');
	}
}
?>