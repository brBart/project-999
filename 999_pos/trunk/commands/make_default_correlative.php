<?php
/**
 * Library containing the MakeDefaultCorrelativeCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/alter_session_object.php');
/**
 * Include the correlative library.
 */
require_once('business/document.php');

/**
 * Defines functionality for setting the correlative as the default correlative.
 * @package Command
 * @author Roberto Oliveros
 */
class MakeDefaultCorrelativeCommand extends AlterSessionObjectCommand{
	/**
	 * Tests if the user has the right to alter the object.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'correlative', 'write');
	}
	
	/**
	 * Alters the desired object.
	 * @param variant $obj
	 */
	protected function alterObject($obj){
		Correlative::makeDefault($obj);
	}
}
?>