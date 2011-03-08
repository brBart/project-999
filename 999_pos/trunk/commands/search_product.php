<?php
/**
 * Library containing the SearchProductCommand class.
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
		$keyword = $request->getProperty('keyword');
		$include_deactivated = (boolean)$request->getProperty('include_deactivated');
		
		$list = ProductSearch::search($keyword, $include_deactivated);
		Page::display(array('list' => $list, 'keyword' => $keyword), 'search_product_list_xml.tpl');
	}
}
?>