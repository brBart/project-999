<?php
/**
 * Library containing the CloseCashRegisterCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/alter_session_object.php');

/**
 * Defines functionality for closing a cash register object.
 * @package Command
 * @author Roberto Oliveros
 */
class CloseCashRegisterCommand extends AlterSessionObjectCommand{
	/**
	 * Tests if the user has the right to alter the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'cash_register', 'close');
	}
	
	/**
	 * Alters the desired object.
	 * @param variant $obj
	 */
	protected function alterObject($obj){
		$obj->close();
	}
}
?>