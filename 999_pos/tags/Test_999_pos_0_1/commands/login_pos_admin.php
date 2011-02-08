<?php
/**
 * Library containing the LoginPosAdminCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/login.php');

/**
 * Displays the login form for the POS Administration side of the system.
 * @package Command
 * @author Roberto Oliveros
 */
class LoginPosAdminCommand extends LoginCommand{
	/**
	 * Returns the name of the template.
	 * @return string
	 */
	protected function getModuleTitle(){
		return POS_ADMIN_TITLE;
	}
	
	/**
	 * Tests if the user has the right to login.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'pos_admin', 'access');
	}
}
?>