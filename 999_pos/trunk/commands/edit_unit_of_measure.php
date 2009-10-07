<?php
/**
 * Library containing the EditOfMeasure command.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/edit_object.php');

/**
 * Tests if a user has the rights to edit a unit of measure.
 * @package Command
 * @author Roberto Oliveros
 */
class EditUnitOfMeasureCommand extends EditObjectCommand{
	/**
	 * Tests if the user has the right to edit the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'unit_of_measure', 'write');
	}
}
?>