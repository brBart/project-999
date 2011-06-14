<?php
/**
 * Library containing the LoginAdminCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/login.php');

/**
 * Displays the login form for the administration side of the system.
 * @package Command
 * @author Roberto Oliveros
 */
class LoginAdminCommand extends LoginCommand{
	/**
	 * Returns the name of the template.
	 * @return string
	 */
	protected function getModuleTitle(){
		return ADMIN_TITLE;
	}
	
	/**
	 * Tests if the user has the right to login.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'admin', 'access');
	}
}
?>