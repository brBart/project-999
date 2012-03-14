<?php
/**
 * Library containing the CancelWithdrawAdjustmentCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/cancel_document.php');

/**
 * Tests if the user has the rights to cancel an withdraw adjustment document.
 * @package Command
 * @author Roberto Oliveros
 */
class CancelWithdrawAdjustmentCommand extends CancelDocumentCommand{
	/**
	 * Tests if the user has the right to cancel.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'withdraw_adjustment', 'cancel');
	}
}
?>