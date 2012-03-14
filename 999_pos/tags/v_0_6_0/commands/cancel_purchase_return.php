<?php
/**
 * Library containing the CancelPurchaseReturnCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/cancel_document.php');

/**
 * Tests if the user has the rights to cancel a purchase return.
 * @package Command
 * @author Roberto Oliveros
 */
class CancelPurchaseReturnCommand extends CancelDocumentCommand{
	/**
	 * Tests if the user has the right to cancel.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'purchase_return', 'cancel');
	}
}
?>