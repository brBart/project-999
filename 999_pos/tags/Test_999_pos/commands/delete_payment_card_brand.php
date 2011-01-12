<?php
/**
 * Library containing the DeletePaymentCardBrandCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/delete_object.php');

/**
 * Implements functionality for deleting payment card brand objects.
 * @package Command
 * @author Roberto Oliveros
 */
class DeletePaymentCardBrandCommand extends DeleteObjectCommand{
	/**
	 * Tests if the user has the right to delete the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'payment_card_brand', 'write');
	}
	
	/**
	 * Deletes the desired object.
	 * @param variant $obj
	 */
	protected function deleteObject($obj){
		PaymentCardBrand::delete($obj);
	}
}
?>