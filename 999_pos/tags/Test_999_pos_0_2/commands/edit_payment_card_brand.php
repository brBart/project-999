<?php
/**
 * Library containing the EditPaymentCardBrandCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/edit_object.php');

/**
 * Tests if a user has the rights to edit a payment card brand.
 * @package Command
 * @author Roberto Oliveros
 */
class EditPaymentCardBrandCommand extends EditObjectCommand{
	/**
	 * Tests if the user has the right to edit the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'payment_card_brand', 'write');
	}
}
?>