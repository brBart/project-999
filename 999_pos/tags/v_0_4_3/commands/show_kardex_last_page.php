<?php
/**
 * Library containing the ShowKardexLastPageCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/show_kardex.php');

/**
 * Defines functionality for obtaining the product's kardex last page.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowKardexLastPageCommand extends ShowKardexCommand{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$product = $helper->getObject((int)$request->getProperty('key'));
		
		$kardex = Kardex::showLastPage($product, $balance, $total_pages, $total_items);
		$first_item = ($total_pages > 0) ? (($total_pages - 1) * ITEMS_PER_PAGE) + 1 : 0;
		$last_item = $total_items;
		$page_items = count($kardex);
		
		$previous_page = ($total_pages <= 1) ? '' : $total_pages - 1;
		$next_page = '';
		
		$this->displayPage($kardex, $total_pages, $total_pages, $total_items, $first_item, $last_item,
				$previous_page, $next_page, $page_items, $balance);
	}
}
?>