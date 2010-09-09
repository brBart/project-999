<?php
/**
 * Library containing the GetDepositCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_cash_register_object.php');
/**
 * For obtaining the objects.
 */
require_once('business/cash.php');

/**
 * Displays the invoice form in idle mode.
 * @package Command
 * @author Roberto Oliveros
 */
class GetDepositCommand extends GetCashRegisterObjectCommand{
	/**
	 * Returns an instance of the object to display.
	 * 
	 * @param string $id
	 * @return variant
	 */
	protected function getObject($id){
		return Deposit::getInstance((int)$id);
	}
	
	/**
	 * Displays the object's form.
	 * @param WorkingDay $workingDay
	 * @param CashRegister $cashRegister
	 * @param Shift $shift
	 * @param string $key
	 * @param variant $obj
	 */
	protected function displayObject(WorkingDay $workingDay, CashRegister $cashRegister,
			Shift $shift, $key, $obj){
				
		$user = $obj->getUser();
		$bank_account = $obj->getBankAccount();
		$bank = $bank_account->getBank();
		
		Page::display(array('module_title' => POS_TITLE, 'back_trace' => $this->getBackTrace(),
				'content' => $this->getTemplate(), 'key' => $key,
				'cash_register_id' => $cashRegister->getId(), 'date' => $workingDay->getDate(),
				'status' => $obj->getStatus(), 'shift' => $shift->getName() . ', ' . $shift->getTimeTable(),
				'cash_register_status' => (int)$cashRegister->isOpen(),
				'id' => $obj->getId(), 'date_time' => $obj->getDateTime(),
				'username' => $user->getUserName(), 'slip_number' => $obj->getNumber(),
				'bank_account' => $bank_account->getNumber() . ', ' . $bank_account->getHolderName(),
				'bank' => $bank->getName()), 'site_pos_html.tpl');
	}
	
	/**
	 * Returns the back trace array.
	 * 
	 * @return array
	 */
	protected function getBackTrace(){
		return array('Inicio', 'Depositos');
	}
	
	/**
	 * Returns the template's name.
	 * 
	 * @return string
	 */
	protected function getTemplate(){
		return 'deposit_form_html.tpl';
	}
	
	/**
	 * Returns the "does not exist" message to display.
	 * 
	 * @return string
	 */
	protected function getMessage(){
		return 'Deposito no existe.';
	}
}
?>