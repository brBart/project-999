<?php
/**
 * Library containing the DeleteUserAccountCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/delete_object.php');

/**
 * Implements functionality for deleting user account objects.
 * @package Command
 * @author Roberto Oliveros
 */
class DeleteUserAccountCommand extends DeleteObjectCommand{
	/**
	 * Tests if the user has the right to delete the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'user_account', 'write');
	}
	
	/**
	 * Deletes the desired object.
	 * @param variant $obj
	 */
	protected function deleteObject($obj){
		if(ActiveSession::getHelper()->getUser()->getUserName() == $obj->getUserName())
			throw new Exception('Cuenta esta en uso, no se puede eliminar.');
		UserAccount::delete($obj);
	}
}
?>