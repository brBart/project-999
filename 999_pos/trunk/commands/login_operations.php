<?php
/**
 * Library containing the ShowLoginOperations command.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/login.php');

/**
 * Displays the login form for the operations side of the system.
 * @package Command
 * @author Roberto Oliveros
 */
class LoginOperationsCommand extends LoginCommand{
	/**
	 * Returns the name of the template.
	 * @return string
	 */
	protected function getTemplate(){
		return 'login_form_operations_html.tpl';
	}
	
	/**
	 * Tests if the user has the right to login.
	 * @param UserAccount $user
	 * @return boolean
	 */
	protected function testRights(UserAccount $user){
		return AccessManager::isAllowed($user, 'operations', 'access');
	}
	
	/**
	 * Take actions on success.
	 */
	protected function displaySuccess(){
		header('Location: index.php');
	}
}
?>