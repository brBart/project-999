<?php
/**
 * Library containing the DeleteManufacturer command.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/delete_object.php');

/**
 * Implements functionality for deleting manufacturer objects.
 * @package Command
 * @author Roberto Oliveros
 */
class DeleteManufacturerCommand extends DeleteObjectCommand{
	/**
	 * Tests if the user has the right to delete an object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'manufacturer', 'write');
	}
	
	/**
	 * Deletes the desired object.
	 * @param variant $obj
	 */
	protected function deleteObject($obj){
		Manufacturer::delete($obj);
	}
}
?>