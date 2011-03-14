<?php
/**
 * Library containing the DeletePaymentCardTypeCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/delete_object.php');

/**
 * Implements functionality for deleting payment card type objects.
 * @package Command
 * @author Roberto Oliveros
 */
class DeletePaymentCardTypeCommand extends DeleteObjectCommand{
	/**
	 * Tests if the user has the right to delete the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'payment_card_type', 'write');
	}
	
	/**
	 * Deletes the desired object.
	 * @param variant $obj
	 */
	protected function deleteObject($obj){
		PaymentCardType::delete($obj);
	}
}
?>