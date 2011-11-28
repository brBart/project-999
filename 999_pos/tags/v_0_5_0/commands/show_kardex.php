<?php
/**
 * Library containing the ShowKardexCommand class.
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
 * For obtaining the kardex.
 */
require_once('business/product.php');

/**
 * Defines common functionality for displaying the product's kardex.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class ShowKardexCommand extends Command{
	/**
	 * Displays the kardex
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function displayPage($kardex, $page, $totalPages, $totalItems, $firstItem, $lastItem,
				$previousPage, $nextPage, $pageItems, $balance){
					
		Page::display(array('kardex' => $kardex, 'page' => $page, 'total_pages' => $totalPages,
				'total_items' => $totalItems, 'first_item' => $firstItem, 'last_item' => $lastItem,
				'previous_page' => $previousPage, 'next_page' => $nextPage, 'page_items' => $pageItems,
				'balance' => $balance), 'kardex_xml.tpl');
	}
}
?>