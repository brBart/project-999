<?php
/**
 * Library containing the CreateCorrelativeCommand class.
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
 * Library with the correlative class.
 */
require_once('business/document.php');

/**
 * Displays the invoice correlative form in edit mode.
 * @package Command
 * @author Roberto Oliveros
 */
class CreateCorrelativeCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$user = $helper->getUser();
		
		if(AccessManager::isAllowed($user, 'correlative', 'write')){
			try{
				$correlative = Correlative::create();
			} catch(Exception $e){
				$this->displayFailure($e->getMessage());
				return;
			}
			
			$key = KeyGenerator::generateKey();
			$helper->setObject($key, $correlative);
			
			$back_trace = array('Inicio', 'Mantenimiento', 'Correlativos');
			Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'blank.tpl',
					'back_trace' => $back_trace, 'second_menu' => 'none',
					'content' => 'correlative_form_html.tpl', 'status' => $correlative->getStatus(),
					'key' => $key, 'back_link' => 'index.php?cmd=show_correlative_menu',
					'forward_link' => 'index.php?cmd=get_correlative'), 'site_html.tpl');
		}
		else{
			$msg = 'Usuario no cuenta con los suficientes privilegios.'; 
			$this->displayFailure($msg);
		}
	}
	
	/**
	 * Display failure in case the user doesn't have rights.
	 * 
	 * @param string $msg
	 */
	protected function displayFailure($msg){
		$back_trace = array('Inicio', 'Mantenimiento', 'Correlativos');
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'main_menu_pos_admin_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'maintenance_menu_pos_admin_html.tpl',
				'content' => 'object_menu_html.tpl', 'notify' => '1', 'type' => 'error', 'message' => $msg,
				'create_link' => 'index.php?cmd=create_correlative',
				'show_list_link' => 'index.php?cmd=show_correlative_list&page=1'), 'site_html.tpl');
	}
}
?>