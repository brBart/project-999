<?php
/**
 * Library containing the PrintDepositCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/print_object.php');
/**
 * For displaying the results.
 */
require_once('presentation/page.php');

/**
 * Displays the deposit data for printing purposes.
 * @package Command
 * @author Roberto Oliveros
 */
class PrintDepositCommand extends PrintObjectCommand{
	/**
	 * Display the object.
	 * @param variant $obj
	 * @param array $details
	 */
	protected function displayObject($obj, $details){		
		$user = $obj->getUser();
		$bank_account = $obj->getBankAccount();
		$bank = $bank_account->getBank();
		$cash_register = $obj->getCashRegister();
		$shift = $cash_register->getShift();
		
		Page::display(array('status' => $obj->getStatus(), 'id' => $obj->getId(),
				'username' => $user->getUserName(), 'date_time' => $obj->getDateTime(),
				'slip_number' => $obj->getNumber(), 'document_name' => 'Deposito',
				'bank_account' => $bank_account->getNumber(), 'bank' => $bank->getName(),
				'cash_register_id' => $cash_register->getId(),
				'shift' => $shift->getName(), 'total' => $obj->getTotal(),
				'total_items' => count($details), 'details' => $details),
				'deposit_print_html.tpl');
	}
}
?>