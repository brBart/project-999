<?php
/**
 * Library containing the PrintLogCommand class.
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
 * Defines common functionality for the print log command derived classes.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class PrintLogCommand extends Command{
	/**
	 * Executes the tasks of the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		if($this->testRights($helper->getUser())){
			$start_date = $request->getProperty('start_date');
			$end_date = $request->getProperty('end_date');
			$page = (int)$request->getProperty('page');

			$list = $this->getList($start_date, $end_date);
				
			$this->displayList($list, $start_date, $end_date, count($list));
		}
		else{
			$msg = 'Insuficientes privilegios.';
			$this->displayFailure($msg);
		}
	}

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
	 * @return array
	 */
	abstract protected function getList($startDate, $endDate);
	
	/**
	 * Displays a message if something goes wrong.
	 * @param strin $msg
	 */
	abstract protected function displayFailure($msg);
	
	/**
	 * Displays the list.
	 * @param array $list
	 * @param string $startDate
	 * @param string $endDate
	 * @param integer $totalItems
	 */
	abstract protected function displayList($list, $startDate, $endDate, $totalItems);
}
?>