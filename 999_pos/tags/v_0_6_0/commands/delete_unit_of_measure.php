<?php
/**
 * Library containing the DeleteUnitOfMeasureCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/delete_object.php');

/**
 * Implements functionality for deleting unit of measure objects.
 * @package Command
 * @author Roberto Oliveros
 */
class DeleteUnitOfMeasureCommand extends DeleteObjectCommand{
	/**
	 * Tests if the user has the right to delete the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'unit_of_measure', 'write');
	}
	
	/**
	 * Deletes the desired object.
	 * @param variant $obj
	 */
	protected function deleteObject($obj){
		UnitOfMeasure::delete($obj);
	}
}
?>