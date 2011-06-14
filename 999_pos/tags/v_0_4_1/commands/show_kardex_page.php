<?php
/**
 * Library containing the ShowKardexPageCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/show_kardex.php');

/**
 * Defines functionality for obtaining the product's kardex page.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowKardexPageCommand extends ShowKardexCommand{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$page = $request->getProperty('page');
		$product = $helper->getObject((int)$request->getProperty('key'));
		
		$kardex = Kardex::showPage($product, $balance, $total_pages, $total_items, (int)$page);
		$page_items = count($kardex);
		// If the page is not empty.
		if($page_items > 0){
			$first_item = (($page - 1) * ITEMS_PER_PAGE) + 1;
			$last_item = ($page == $total_pages) ? $total_items : $page * ITEMS_PER_PAGE;
		}
		else{
			$first_item = 0;
			$last_item = 0;
			$page = 0;
		}
		
		$previous_page = ($page <= 1) ? '' : $page - 1;
		$next_page = ($page == $total_pages) ? '' : $page + 1;
		
		$this->displayPage($kardex, $page, $total_pages, $total_items, $first_item, $last_item,
				$previous_page, $next_page, $page_items, $balance);
	}
}
?>