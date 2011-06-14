<?php
/**
 * Library containing the DeleteBranchCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/delete_object.php');

/**
 * Implements functionality for deleting branch objects.
 * @package Command
 * @author Roberto Oliveros
 */
class DeleteBranchCommand extends DeleteObjectCommand{
	/**
	 * Tests if the user has the right to delete the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'branch', 'write');
	}
	
	/**
	 * Deletes the desired object.
	 * @param variant $obj
	 */
	protected function deleteObject($obj){
		Branch::delete($obj);
	}
}
?>