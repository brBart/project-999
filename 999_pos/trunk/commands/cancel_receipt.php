<?php
/**
 * Library containing the CancelReceipt command.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/cancel_document.php');

/**
 * Tests if the user has the rights to cancel a receipt.
 * @package Command
 * @author Roberto Oliveros
 */
class CancelReceiptCommand extends CancelDocumentCommand{
	/**
	 * Tests if the user has the right to cancel.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'receipt', 'cancel');
	}
}
?>