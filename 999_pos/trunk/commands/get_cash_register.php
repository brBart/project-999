<?php
/**
 * Library containing the GetCashRegisterCommand class.
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

require_once('business/cash.php');

/**
 * Command to obtain a cash register.
 * @package Command
 * @author Roberto Oliveros
 */
class GetCashRegisterCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$id = $request->getProperty('shift_id');
		// Check if the user selected a shift.
		if($id == ''){
			$msg = 'Seleccione un turno.';
			Page::display(array('message' => $msg), 'error_xml.tpl');
			return;
		}
		
		$shift = Shift::getInstance((int)$id);
		// If id is not valid.
		if(is_null($shift)){
			$msg = 'Turno no existe.';
			Page::display(array('message' => $msg), 'error_xml.tpl');
		}
		else{
			// Sorry, bad practice necessary.
			$working_day = $helper->getWorkingDay();
		
			try{
				$cash_register = $working_day->getCashRegister($shift);
			} catch(Exception $e){
				$msg = $e->getMessage();
				Page::display(array('message' => $msg), 'error_xml.tpl');
				return;
			}
			
			$key = KeyGenerator::generateKey();
			$helper->setObject($key, $cash_register);
		
			Page::display(array('key' => $key), 'object_key_xml.tpl');
		}
	}
}
?>