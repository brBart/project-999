<?php
/**
 * Library containing the DeleteReserveCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/alter_object.php');
/**
 * For obtaining the reserve object.
 */
require_once('business/document.php');

/**
 * Implements functionality for deleting a reserve.
 * @package Command
 * @author Roberto Oliveros
 */
class DeleteReserveCommand extends AlterObjectCommand{
	/**
	 * Tests if the user has the right to alter the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'reserve', 'write');
	}
	
	/**
	 * Alters the desired object.
	 * @param string $id
	 */
	protected function alterObject($id){
		$reserve = Reserve::getInstance((int)$id);
		if(!is_null($reserve))
			Reserve::delete($reserve);
		else
			throw new Exception('Reservado no existe.');
	}
}
?>