<?php
/**
 * Library containing the GetObjectPage base class command.
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
 * For displaying the object details.
 */
require_once('business/itemized.php');

/**
 * Defines common functionality for obtaining an object's details page.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class GetObjectPageCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$page = $request->getProperty('page');
		$obj = $helper->getObject((int)$request->getProperty('key'));
		
		$details = DetailsPrinter::showPage($obj, $total_pages, $total_items, (int)$page);
		$page_items = count($details);
		// If the page is not empty.
		if($page_items > 0){
			$first_item = (($page - 1) * ITEMS_PER_PAGE) + 1;
			$last_item = ($page == $total_pages) ? $total_items : $page * ITEMS_PER_PAGE;
		}
		else{
			// If it is empty, check if it is not the only page.
			if($page > 1 && $total_items > 0){
				// If it is not the only page, return the last page instead. 
				$details = DetailsPrinter::showLastPage($obj, $total_pages, $total_items);
				$first_item = ($total_pages > 0) ? (($total_pages - 1) * ITEMS_PER_PAGE) + 1 : 0;
				$last_item = $total_items;
				$page_items = count($details);
				$page = $total_pages;
			}
			// If it was the only page.
			else{
				$first_item = 0;
				$last_item = 0;
				$page = 0;
			}
		}
		
		$previous_page = ($page <= 1) ? '' : $page - 1;
		$next_page = ($page == $total_pages) ? '' : $page + 1;
		
		Page::display(array('details' => $details, 'page' => $page, 'total_pages' => $total_pages,
				'total_items' => $total_items, 'first_item' => $first_item, 'last_item' => $last_item,
				'previous_page' => $previous_page, 'next_page' => $next_page, 'page_items' => $page_items,
				'total' => $obj->getTotal()), $this->getTemplate());
	}
	
	/**
	 * Returns the name of the template to use.
	 * @return string
	 */
	abstract protected function getTemplate();
}
?>