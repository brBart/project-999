<?php
/**
 * Library containing the CreateDiscountCommand class.
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
 * For creating the discount object.
 */
require_once('business/document.php');

/**
 * Defines functionality for creating a invoice's discount.
 * @package Command
 * @author Roberto Oliveros
 */
class CreateDiscountCommand extends Command{
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
				
				if(AccessManager::isAllowed($user, 'discount', 'write')){
					$discount = new Discount($user);
					$key = KeyGenerator::generateKey();
					$helper->setObject($key, $discount);
					Page::display(array('key' => $key), 'object_key_xml.tpl');
				}
				else{
					$msg = 'Insuficientes privilegios.';
					Page::display(array('message' => $msg), 'error_xml.tpl');
				}
			}
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('message' => $msg), 'error_xml.tpl');
		}
	}
}
?>