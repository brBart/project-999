<?php
/**
 * Library containing the Login command.
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
 * Defines common functionality for the login command derived classes.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class LoginCommand extends Command{
	/**
	 * Executes the tasks of the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$type = $request->getProperty('type');
		if($type == 'xml'){
			// Is an ajax request.
			$msg = 'La sesi&oacute;n ha terminado.';
			header('Content-Type: text/xml');
			Page::display(array('message' => $msg), 'logout_xml.tpl');
			return;
		}
		
		$module_title = $this->getModuleTitle();
		
		// Verify if the submit button of the login form was pressed.
		if(is_null($request->getProperty('login'))){
			// Check if the user was kicked out or was sending a command.
			if(!is_null($request->getProperty('cmd'))){
				$notify = 1;
				$msg = 'La sesi&oacute;n ha terminado. Ingrese de nuevo.';
			}
			else
				$notify = 0;
			
			Page::display(array('module_title' => $module_title, 'notify' => $notify, 'message' => $msg),
					'login_form_html.tpl');
			return;
		}
		
		// If the submit button was pressed.
		$username = $request->getProperty('username');
		$password = $request->getProperty('password');
		
		try{
			if(!UserAccountUtility::isValid($username, $password)){
				$msg = 'Usuario o contrase&ntilde;a inv&aacute;lidos.';
				Page::display(array('module_title' => $module_title, 'username' => $username, 'notify' => '1',
						'message' => $msg), 'login_form_html.tpl');
			}
			else{
				$user = UserAccount::getInstance($username);
				
				if(!$this->testRights($user)){
					$msg = 'Acceso denegado.';
					Page::display(array('module_title' => $module_title, 'username' => $username,
							'notify' => '1', 'message' => $msg), 'login_form_html.tpl');
				}
				else{
					$helper->setUser($user);
					header('Location: index.php');
				}
			}
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('module_title' => $module_title, 'username' => $username, 'notify' => '1',
					'message' => $msg), 'login_form_html.tpl');
		}
	}
	
	/**
	 * Returns the module's name.
	 * @return string
	 */
	abstract protected function getModuleTitle();
	
	/**
	 * Tests if the user has the right to login.
	 * @param UserAccount $user
	 * @return boolean
	 */
	abstract protected function testRights(UserAccount $user);
}
?>