<?php
/**
 * Library containing the GetCashRegisterObjectCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_object.php');

/**
 * Defines common functionality for displaying the objects.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class GetCashRegisterObjectCommand extends Command{
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
		
		$obj = $this->getObject($request->getProperty('id'));
		
		if(!is_null($obj)){
			$key = KeyGenerator::generateKey();
			$helper->setObject($key, $obj);
			
			$this->displayObject($working_day, $cash_register, $shift, $key, $obj);
		}
		else{
			Page::display(array('module_title' => POS_TITLE, 'back_trace' => $this->getBackTrace(),
					'content' => $this->getTemplate(), 'cash_register_id' => $cash_register->getId(),
					'date' => $working_day->getDate(), 'status' => '1',
					'shift' => $shift->getName() . ', ' . $shift->getTimeTable(),
					'cash_register_status' => (int)$cash_register->isOpen(), 'notify' => '1',
					'type' => 'error', 'message' => $this->getMessage()), 'site_pos_html.tpl');
		}
	}

	/**
	 * Returns an instance of the object to display.
	 * 
	 * @param string $id
	 * @return variant
	 */
	abstract protected function getObject($id);
	
	/**
	 * Displays the object's form.
	 * @param WorkingDay $workingDay
	 * @param CashRegister $cashRegister
	 * @param Shift $shift
	 * @param string $key
	 * @param variant $obj
	 */
	abstract protected function displayObject(WorkingDay $workingDay, CashRegister $cashRegister,
			Shift $shift, $key, $obj);

	/**
	 * Returns the back trace array.
	 * 
	 * @return array
	 */
	abstract protected function getBackTrace();
	
	/**
	 * Returns the template's name.
	 * 
	 * @return string
	 */
	abstract protected function getTemplate();

	/**
	 * Returns the "does not exist" message to display.
	 * 
	 * @return string
	 */
	abstract protected function getMessage();
}
?>