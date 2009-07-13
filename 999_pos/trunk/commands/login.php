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
		if(is_null($request->getProperty('login'))){
			Page::display(array('success' => '1'), $this->getTemplate());
			return;
		}

		$username = $request->getProperty('username');
		$password = $request->getProperty('password');
		
		try{
			if(!UserAccountUtility::isValid($username, $password)){
				$msg = 'Usuario o contrase&ntilde;a inv&aacute;lido.';
				Page::display(array('success' => '0', 'message' => $msg), $this->getTemplate());
			}
			else{
				$user = UserAccount::getInstance($username);
				
				if(!$this->testRights($user)){
					$msg = 'Acceso denegado.';
					Page::display(array('success' => '0', 'message' => $msg), $this->getTemplate());
				}
				else{
					$helper->setUser($user);
					$this->displaySuccess();
				}
			}
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('success' => '0', 'message' => $msg), $this->getTemplate());
		}
	}
	
	/**
	 * Returns the name of the template.
	 * @return string
	 */
	abstract protected function getTemplate();
	
	/**
	 * Tests if the user has the right to login.
	 * @param UserAccount $user
	 * @return boolean
	 */
	abstract protected function testRights(UserAccount $user);
	
	/**
	 * Take actions on success.
	 */
	abstract protected function displaySuccess();
}
?>