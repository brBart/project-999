<?php
/**
 * Library containing the GetComparisonPageCommand base class.
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
 * Defines common functionality for obtaining a comparison's details page.
 * @package Command
 * @author Roberto Oliveros
 */
class GetComparisonPageCommand extends Command{
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
		
		$params = array('details' => $details, 'page' => $page, 'total_pages' => $total_pages,
				'total_items' => $total_items, 'first_item' => $first_item, 'last_item' => $last_item,
				'previous_page' => $previous_page, 'next_page' => $next_page, 'page_items' => $page_items,
				'physical_total' => $obj->getPhysicalTotal(), 'system_total' => $obj->getSystemTotal(),
				'total_diference' => $obj->getTotalDiference());
		
		$params = array_merge($params, $this->getObjectParams($obj));
		
		Page::display($params, 'comparison_page_xml.tpl');
	}
	
	/**
	 * Returns the params to display for the object.
	 * @param variant $obj
	 */
	protected function getObjectParams($obj){
		return array();
	}
}
?>