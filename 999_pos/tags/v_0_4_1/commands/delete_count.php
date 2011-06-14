<?php
/**
 * Library containing the DeleteCountCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/delete_object.php');

/**
 * Implements functionality for deleting a count document.
 * @package Command
 * @author Roberto Oliveros
 */
class DeleteCountCommand extends DeleteObjectCommand{
	/**
	 * Tests if the user has the right to delete the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'count', 'write');
	}
	
	/**
	 * Deletes the desired object.
	 * @param variant $obj
	 */
	protected function deleteObject($obj){
		Count::delete($obj);
	}
}
?>