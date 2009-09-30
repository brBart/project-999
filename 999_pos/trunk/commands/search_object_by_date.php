<?php
/**
 * Library containing the SearchObjectByDate base class command.
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
 * Defines common functionality for the show list derived classes.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class SearchObjectByDateCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$start_date = $request->getProperty('start_date');
		$end_date = $request->getProperty('end_date');
		$page = (int)$request->getProperty('page');
		$list = $this->getList($start_date, $end_date, $total_pages, $total_items, $page);
		
		if($total_items > 0){
			$first_item = (($page - 1) * ITEMS_PER_PAGE) + 1;
			$last_item = ($page == $total_pages) ? $total_items : $page * ITEMS_PER_PAGE;
			
			// For back link purposes.
			$actual_cmd = $request->getProperty('cmd');
			
			$link = 'index.php?cmd=' . $actual_cmd . '&page=';
			$previous_link = ($page == 1) ? '' : $link . ($page - 1);
			$next_link = ($page == $total_pages) ? '' : $link . ($page + 1);
			
			$this->displayList($list, $total_pages, $total_items, $page, $first_item, $last_item, $previous_link,
					$next_link, $actual_cmd);
		}
		else
			$this->displayFailure();
	}
	
	/**
	 * Returns a list with information.
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	abstract protected function getList(&$totalPages, &$totalItems, $page);
	
	/**
	 * Displays an empty list.
	 */
	abstract protected function displayFailure();
	
	/**
	 * Displays the list.
	 * @param array $list
	 * @param integer $totalPages
	 * @param integer $totalItems
	 * @param integer $page
	 * @param integer $firstItem
	 * @param integer $lastItem
	 * @param string $previousLink
	 * @param string $nextLink
	 */
	abstract protected function displayList($list, $totalPages, $totalItems, $page, $firstItem, $lastItem,
			$previousLink, $nextLink, $actualCmd);
}
?>