<?php
/**
 * Library containing the ShowLogCommand class.
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
 * For user accounts validations.
 */
require_once('business/user_account.php');
/**
 * For the log objects.
 */
require_once('business/various.php');

/**
 * Defines common functionality for the show log command derived classes.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class ShowLogCommand extends Command{
	/**
	 * Holds the request object.
	 * @var Request
	 */
	protected $_mRequest;
	
	/**
	 * Executes the tasks of the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		if(is_null($request->getProperty('show_reference'))){
			$this->showForm();				
			return;
		}	
		
		$start_date = $request->getProperty('start_date');
		$end_date = $request->getProperty('end_date');
		
		if($this->testRights($helper->getUser())){
			$page = (int)$request->getProperty('page');
			
			try{
				$list = $this->getList($start_date, $end_date, $total_pages, $total_items, $page);
			} catch(Exception $e){
				$msg = $e->getMessage();
				$this->displayFailure($msg, $start_date, $end_date);
				return;
			}
			
			if($total_items > 0){
				$first_item = (($page - 1) * ITEMS_PER_PAGE) + 1;
				$last_item = ($page == $total_pages) ? $total_items : $page * ITEMS_PER_PAGE;
				
				// For back link purposes.
				$actual_cmd = $request->getProperty('cmd');
				
				$link = 'index.php?cmd=' . $actual_cmd . '&show_reference&page=';
				$dates = '&start_date=' . urlencode($start_date) . '&end_date=' . urlencode($end_date);
				$previous_link = ($page == 1) ? '' : $link . ($page - 1) . $dates;
				$next_link = ($page == $total_pages) ? '' : $link . ($page + 1) . $dates;
				
				$this->displayList($list, $start_date, $end_date, $total_pages, $total_items, $page, $first_item,
						$last_item, $previous_link, $next_link, $actual_cmd);
			}
			else
				$this->displayEmpty();
		}
		else{
			$msg = 'Insuficientes privilegios.';
			$this->displayFailure($msg, $start_date, $end_date);
		}
	}
	
	/**
	 * Shows the empty form.
	 */
	abstract protected function showForm();

	/**
	 * Tests if the user has the right to cancel the document.
	 * @param UserAccount $user
	 * @return boolean
	 */
	abstract protected function testRights(UserAccount $user);
	
	/**
	 * Returns a list with information.
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer &$totalPages
	 * @param integer &$totalItems
	 * @param integer $page
	 * @return array
	 */
	abstract protected function getList($startDate, $endDate, &$totalPages, &$totalItems, $page);
	
	/**
	 * Displays a message if something goes wrong.
	 * @param string $msg
	 * @param string $startDate
	 * @param string $endDate
	 */
	abstract protected function displayFailure($msg, $startDate, $endDate);
	
	/**
	 * Displays an empty list.
	 */
	abstract protected function displayEmpty();
	
	/**
	 * Displays the list.
	 * @param array $list
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer $totalPages
	 * @param integer $totalItems
	 * @param integer $page
	 * @param integer $firstItem
	 * @param integer $lastItem
	 * @param string $previousLink
	 * @param string $nextLink
	 */
	abstract protected function displayList($list, $startDate, $endDate, $totalPages, $totalItems, $page,
			$firstItem, $lastItem, $previousLink, $nextLink, $actualCmd);
}
?>