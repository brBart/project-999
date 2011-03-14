<?php
/**
 * Library containing the EditVatCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/edit_object.php');

/**
 * Tests if a user has the rights to edit the V.A.T. percentage.
 * @package Command
 * @author Roberto Oliveros
 */
class EditVatCommand extends EditObjectCommand{
	/**
	 * Tests if the user has the right to edit the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'vat', 'write');
	}
}
?>