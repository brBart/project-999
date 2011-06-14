<?php
/**
 * Library containing the DeleteShiftCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/delete_object.php');

/**
 * Implements functionality for deleting shift objects.
 * @package Command
 * @author Roberto Oliveros
 */
class DeleteShiftCommand extends DeleteObjectCommand{
	/**
	 * Tests if the user has the right to delete the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'shift', 'write');
	}
	
	/**
	 * Deletes the desired object.
	 * @param variant $obj
	 */
	protected function deleteObject($obj){
		Shift::delete($obj);
	}
}
?>