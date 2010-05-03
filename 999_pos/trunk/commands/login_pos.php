<?php
/**
 * Library containing the LoginPosCommand class.
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
 * For opening the working day on the cash register.
 */
require_once('business/cash.php');

/**
 * Defines functionality for the login pos command.
 * @package Command
 * @author Roberto Oliveros
 */
class LoginPosCommand extends Command{
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
			Page::display(array('message' => $msg), 'logout_xml.tpl');
			return;
		}
		
		$module_title = POS_TITLE;
		
		// Verify if the submit button of the login form was pressed.
		if(is_null($request->getProperty('login'))){
			// Check if the user was kicked out or was sending a command.
			if(!is_null($request->getProperty('cmd'))){
				$notify = 1;
				$msg = 'La sesi&oacute;n ha terminado. Ingrese de nuevo.';
			}
			
			Page::display(array('module_title' => $module_title, 'notify' => $notify,
					'message' => $msg, 'date' => date('d/m/Y')), 'login_pos_form_html.tpl');
			return;
		}
		
		// If the submit button was pressed.
		$username = $request->getProperty('username');
		$password = $request->getProperty('password');
		$date = $request->getProperty('date');
		
		try{
			if(!UserAccountUtility::isValid($username, $password)){
				$msg = 'Usuario o contrase&ntilde;a inv&aacute;lidos.';
				Page::display(array('module_title' => $module_title, 'username' => $username,
				'date' => $date, 'notify' => '1', 'message' => $msg), 'login_pos_form_html.tpl');
			}
			else{
				$user = UserAccount::getInstance($username);
				
				if(AccessManager::isAllowed($user, 'pos', 'access')){
					$working_day = WorkingDay::getInstance($date);
					$key = KeyGenerator::generateKey();
					
					$helper->setUser($user);
					$helper->setObject($key, $working_day);
					header('Location: index.php?wday_key=' . $key);
				}
				else{
					$msg = 'Acceso denegado.';
					Page::display(array('module_title' => $module_title, 'username' => $username,
							'date' => $date, 'notify' => '1', 'message' => $msg), 'login_pos_form_html.tpl');
				}
			}
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('module_title' => $module_title, 'username' => $username,
					'date' => $date, 'notify' => '1', 'message' => $msg), 'login_pos_form_html.tpl');
		}
	}
}
?>