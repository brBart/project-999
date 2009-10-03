<?php
/**
 * Library containing the CancelDocument command.
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
 * Defines common functionality for the cancel document command derived classes.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class CancelDocumentCommand extends Command{
	/**
	 * Executes the tasks of the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$username = $request->getProperty('username');
		$password = $request->getProperty('password');
		
		try{
			if(!UserAccountUtility::isValid($username, $password)){
				$msg = 'Usuario o contrase&ntilde;a inv&aacute;lidos.';
				Page::display(array('message' => $msg), 'failure_xml.tpl');
			}
			else{
				$user = UserAccount::getInstance($username);
				
				if($this->testRights($user)){
					$obj = $helper->getObject((int)$request->getProperty('key'));
					$obj->cancel($user);
					Page::display(array(), 'success_xml.tpl');
				}
				else{
					$msg = 'Insuficientes privilegios.';
					Page::display(array('message' => $msg), 'failure_xml.tpl');
				}
			}
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('message' => $msg), 'error_xml.tpl');
		}
	}

	/**
	 * Tests if the user has the right to cancel the document.
	 * @param UserAccount $user
	 * @return boolean
	 */
	abstract protected function testRights(UserAccount $user);
}
?>