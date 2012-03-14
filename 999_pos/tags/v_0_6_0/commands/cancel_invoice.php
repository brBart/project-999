<?php
/**
 * Library containing the CancelInvoiceCommand class.
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
 * Tests if the user has the rights to cancel an invoice.
 * @package Command
 * @author Roberto Oliveros
 */
class CancelInvoiceCommand extends Command{
	/**
	 * Executes the tasks of the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$username = $request->getProperty('username');
		$password = $request->getProperty('password');
		$reason = $request->getProperty('reason');
		
		try{
			if(!UserAccountUtility::isValid($username, $password)){
				$msg = 'Usuario o contrase&ntilde;a inv&aacute;lidos.';
				Page::display(array('message' => $msg), 'failure_xml.tpl');
			}
			else{
				$user = UserAccount::getInstance($username);
				
				if(AccessManager::isAllowed($user, 'invoice', 'cancel')){
					$invoice = $helper->getObject((int)$request->getProperty('key'));
					$invoice->cancel($user, $reason);
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
}
?>