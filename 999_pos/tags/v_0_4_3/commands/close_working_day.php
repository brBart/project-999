<?php
/**
 * Library containing the CloseWorkingDayCommand base class.
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
 * Defines functionality for closing a working day object.
 * @package Command
 * @author Roberto Oliveros
 */
class CloseWorkingDayCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$user = $helper->getUser();
		
		if(AccessManager::isAllowed($user, 'working_day', 'close')){
			// Sorry, bad practice necessary.
			$working_day = $helper->getWorkingDay();
			
			try{
				$working_day->close();
			} catch(Exception $e){
				$msg = $e->getMessage();
				Page::display(array('message' => $msg), 'error_xml.tpl');
				return;
			}
			
			Page::display(array(), 'success_xml.tpl');
		}
		else{
			$msg = 'Usuario no cuenta con los suficientes privilegios.';
			Page::display(array('message' => $msg), 'error_xml.tpl');
		}
	}
}
?>