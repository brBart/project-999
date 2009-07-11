<?php
/**
 * Library containing the ShowLoginOperations command.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('presentation/command.php');
/**
 * For displaying the results.
 */
require_once('presentation/page.php');
/**
 * For user accounts validations.
 */
require_once('business/user_account.php');

/**
 * Displays the login form for the operations side of the system.
 * @package Command
 * @author Roberto Oliveros
 */
class LoginOperationsCommand extends Command{
	/**
	 * (non-PHPdoc)
	 * @see 999_web/presentation/commands/Command#execute($request, $helper)
	 */
	public function execute(Request $request, SessionHelper $helper){
		if(is_null($request->getProperty('login')))
			Page::display(array('success' => '1'), 'login_form_operations_html.tpl');
		
		$username = $request->getProperty('username');
		$password = $request->getProperty('password');
		if(!UserAccountUtility::isValid($username, $password)){
			$msg = 'Usuario o contrase&ntilde;a inv&aacute;lido.';
			Page::display(array('success' => '0', 'message' => $msg), 'login_form_operations_html.tpl');
		}
	}
}
?>