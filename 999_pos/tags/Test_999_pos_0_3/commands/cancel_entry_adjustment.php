<?php
/**
 * Library containing the CancelEntryAdjustmentCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/cancel_document.php');

/**
 * Tests if the user has the rights to cancel an entry adjustment document.
 * @package Command
 * @author Roberto Oliveros
 */
class CancelEntryAdjustmentCommand extends CancelDocumentCommand{
	/**
	 * Tests if the user has the right to cancel.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'entry_adjustment', 'cancel');
	}
}
?>