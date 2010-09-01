<?php
/**
 * Library containing the CreateDepositCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/create_cash_register_object.php');
/**
 * Library with the deposit class.
 */
require_once('business/cash.php');

/**
 * Creates a deposit and returns its information.
 * @package Command
 * @author Roberto Oliveros
 */
class CreateDepositCommand extends CreateCashRegisterObjectCommand{
	/**
	 * Tests if the user has the right to create the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'deposit', 'write');
	}
	
	/**
	 * Creates the desired object.
	 * @return variant
	 */
	protected function createObject(CashRegister $cashRegister){
		return new Deposit($cashRegister);
	}
}
?>