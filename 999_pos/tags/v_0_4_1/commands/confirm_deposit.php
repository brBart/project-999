<?php
/**
 * Library containing the ConfirmDepositCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/alter_object.php');
/**
 * For obtaining the deposit object.
 */
require_once('business/cash.php');

/**
 * Implements functionality for confirming a deposit.
 * @package Command
 * @author Roberto Oliveros
 */
class ConfirmDepositCommand extends AlterObjectCommand{
	/**
	 * Tests if the user has the right to alter the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'deposit', 'confirm');
	}
	
	/**
	 * Alters the desired object.
	 * @param string $id
	 */
	protected function alterObject($id){
		$deposit = Deposit::getInstance((int)$id);
		if(!is_null($deposit))
			$deposit->confirm();
		else
			throw new Exception('Deposito no existe.');
	}
}
?>