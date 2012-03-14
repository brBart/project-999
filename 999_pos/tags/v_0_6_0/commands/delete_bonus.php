<?php
/**
 * Library containing the DeleteBonusCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/alter_object.php');
/**
 * For obtaining the bonus object.
 */
require_once('business/product.php');

/**
 * Implements functionality for deleting a bonus.
 * @package Command
 * @author Roberto Oliveros
 */
class DeleteBonusCommand extends AlterObjectCommand{
	/**
	 * Tests if the user has the right to alter the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'bonus', 'write');
	}
	
	/**
	 * Alters the desired object.
	 * @param string $id
	 */
	protected function alterObject($id){
		$bonus = Bonus::getInstance((int)$id);
		if(!is_null($bonus))
			Bonus::delete($bonus);
		else
			throw new Exception('Oferta no existe.');
	}
}
?>