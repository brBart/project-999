<?php
/**
 * Library containing the ChangePasswordCommand class.
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
 * Command to display the change password form.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class ChangePasswordCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$module_title = $this->getModuleTitle();
		$user = $helper->getUser();
		$username = $user->getUserName();
		$back_trace = array('Inicio', 'Constrase&ntilde;a');
		
		if(is_null($request->getProperty('change_password'))){
			Page::display(array('module_title' => $module_title, 'main_menu' => 'blank.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'none',
				'content' => 'change_password_form_html.tpl', 'username' => $username,
				'forward_link' => $this->getForwardLink()), 'site_html.tpl');
			return;
		}
		
		// If the submit button was pressed.
		$password = $request->getProperty('password');
		$new_password = $request->getProperty('new_password');
		$confirm_password = $request->getProperty('confirm_password');
		
		// Check if data was entered correctly.
		if($new_password == '')
			$msg = 'Nueva contrase&ntilde;a inv&aacute;lida. Valor no puede ser vacio.';
		if($new_password != $confirm_password)
			$msg = 'Nueva contrase&ntilde;a y confirmaci&oacute;n no coinciden.';
		if($password == '')
			$msg = 'Contrase&ntilde;a actual inv&aacute;lida. Valor no puede ser vacio.';
		
		// If was not.
		if($msg != ''){
			Page::display(array('module_title' => $module_title, 'main_menu' => 'blank.tpl',
					'back_trace' => $back_trace, 'second_menu' => 'none',
					'content' => 'change_password_form_html.tpl', 'username' => $username,
					'forward_link' => $this->getForwardLink(), 'notify' => '1',
					'type' => 'failure', 'message' => $msg), 'site_html.tpl');
			return;
		}
		
		// If it was entered correctly.
		try{
			UserAccountUtility::changePassword($user, $password, $new_password);
			$back_trace = array('Inicio');
			$msg = 'Su contrase&ntilde;a cambio correctamente.';
			Page::display(array('module_title' => $module_title, 'main_menu' => $this->getMainMenuTemplate(),
					'back_trace' => $back_trace, 'second_menu' => 'none', 'content' => 'none',
					'notify' => '1', 'type' => 'success', 'message' => $msg), 'site_html.tpl');
		} catch(ValidateException $e){
			$msg = $e->getMessage();
			Page::display(array('module_title' => $module_title, 'main_menu' => 'blank.tpl',
					'back_trace' => $back_trace, 'second_menu' => 'none',
					'content' => 'change_password_form_html.tpl', 'username' => $username,
					'forward_link' => $this->getForwardLink(), 'notify' => '1',
					'type' => 'failure', 'message' => $msg), 'site_html.tpl');
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('module_title' => $module_title, 'main_menu' => 'blank.tpl',
					'back_trace' => $back_trace, 'second_menu' => 'none',
					'content' => 'change_password_form_html.tpl', 'username' => $username,
					'forward_link' => $this->getForwardLink(), 'notify' => '1',
					'type' => 'error', 'message' => $msg), 'site_html.tpl');
		}
	}
	
	/**
	 * Returns the module's name.
	 * @return string
	 */
	abstract protected function getModuleTitle();
	
	/**
	 * Returns the main menu template file name.
	 * @return string
	 */
	abstract protected function getMainMenuTemplate();
	
	/**
	 * Returns the link to forward the form on action.
	 * @return string
	 */
	abstract protected function getForwardLink();
}
?>