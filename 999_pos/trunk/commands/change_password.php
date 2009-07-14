<?php
/**
 * Library containing the ChangePassword command.
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
		$username = $helper->getUser()->getUserName();
		$back_trace = array('Inicio', 'Constrase&ntilde;a');
		
		if(is_null($request->getProperty('change_password'))){
			Page::display(array('module_title' => $module_title, 'main_menu' => 'blank.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'blank.tpl',
				'content' => 'change_password_form.tpl', 'username' => $username, 'success' => '1'),
				'site_html.tpl');
			return;
		}
		
		
	}
	
	/**
	 * Returns the module's name.
	 * @return string
	 */
	abstract protected function getModuleTitle();
}
?>