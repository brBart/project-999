<?php
/**
 * Library containing the SetBankAccountDepositCommand base class.
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
 * For obtaining the bank account object.
 */
require_once('business/cash.php');

/**
 * Defines functionality for setting a bank account to a deposit.
 * @package Command
 * @author Roberto Oliveros
 */
class SetBankAccountDepositCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$bank_account_id = $request->getProperty('bank_account_id');
		$bank_account = BankAccount::getInstance($bank_account_id);
		$deposit = $helper->getObject((int)$request->getProperty('key'));
		
		try{
			$deposit->setBankAccount($bank_account);
		} catch(ValidateException $e){
			$msg = $e->getMessage();
			Page::display(array('success' => '0', 'message' => $msg), 'validate_xml.tpl');
			return;
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('message' => $msg), 'error_xml.tpl');
			return;
		}
		
		$bank = $bank_account->getBank();
			
		Page::display(array('bank' => $bank->getName()), 'set_bank_account_deposit_xml.tpl');
	}
}
?>