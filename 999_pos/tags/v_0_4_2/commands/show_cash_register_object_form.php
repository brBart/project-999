<?php
/**
 * Library containing the ShowCashRegisterObjectFormCommand class.
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
 * Command with common functionaltity to display the cash register status and a
 * empty document form.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class ShowCashRegisterObjectFormCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		// Sorry, bad practice necessary.
		$working_day = $helper->getWorkingDay();
		$cash_register = $helper->getObject((int)$request->getProperty('register_key'));
		$shift = $cash_register->getShift();
		
		Page::display(array('module_title' => POS_TITLE, 'back_trace' => $this->getBackTrace(),
				'content' => $this->getTemplate(), 'cash_register_id' => $cash_register->getId(),
				'date' => $working_day->getDate(), 'status' => '1',
				'shift' => $shift->getName() . ', ' . $shift->getTimeTable(),
				'cash_register_status' => (int)$cash_register->isOpen(), 'notify' => '1',
				'type' => 'info', 'message' => $this->getMessage()), 'site_pos_html.tpl');
	}
	
	/**
	 * Returns an array with the back trace strings to display.
	 * @return array
	 */
	abstract protected function getBackTrace();
	
	/**
	 * Returns the name of the template to use.
	 * @return string
	 */
	abstract protected function getTemplate();
	
	/**
	 * Return string with the message to display.
	 * @return string
	 */
	abstract protected function getMessage();
}
?>