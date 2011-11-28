<?php
/**
 * Library containing the PrintResolutionLogCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/print_log.php');

/**
 * Implements functionality for printing the log.
 * @package Command
 * @author Roberto Oliveros
 */
class PrintResolutionLogCommand extends PrintLogCommand{
	/**
	 * Tests if the user has the right to cancel.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'resolution_log', 'read');
	}
	
	/**
	 * Returns a list with information.
	 * @param string $startDate
	 * @param string $endDate
	 * @return array
	 */
	protected function getList($startDate, $endDate){
		return ResolutionList::getList($startDate, $endDate);
	}
	
	/**
	 * Display failure in case an error occurs.
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		Page::display(array('notify' => '1', 'type' => 'error', 'message' => $msg),
				'resolution_log_print_html.tpl');
	}
	
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
	 * @param string $actualCmd
	 */
	protected function displayList($list, $startDate, $endDate, $totalItems){
		Page::display(array('log_name' => 'Resoluciones', 'list' => $list, 'start_date' => $startDate,
				'end_date' => $endDate, 'total_items' => $totalItems), 'resolution_log_print_html.tpl');
	}
}
?>