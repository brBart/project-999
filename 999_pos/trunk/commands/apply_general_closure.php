<?php
/**
 * Library containing the ApplyGeneralClosureCommand class.
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
 * For obtaining the event.
 */
require_once('business/various.php');

/**
 * Command to display the general closure form.
 * @package Command
 * @author Roberto Oliveros
 */
class ApplyGeneralClosureCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Herramientas', 'Tareas');
		
		if(is_null($request->getProperty('apply_closure'))){
			Page::display(array('module_title' => ADMIN_TITLE, 'main_menu' => 'blank.tpl',
					'back_trace' => $back_trace, 'second_menu' => 'none',
					'back_link' => 'index.php?cmd=show_task_menu_admin',
					'content' => 'general_closure_form_html.tpl', 'task_name' => 'Cierre General'),
					'site_html.tpl');
			return;
		}
		
		try{
			ClosingEvent::apply($request->getProperty('days'));
			$back_trace = array('Inicio');
			$msg = 'El cierre general se realizo exitosamente.';
			Page::display(array('module_title' => ADMIN_TITLE, 'main_menu' => 'main_menu_admin_html.tpl',
					'back_trace' => $back_trace, 'second_menu' => 'none', 'content' => 'none',
					'notify' => '1', 'type' => 'success', 'message' => $msg), 'site_html.tpl');
		} catch(ValidateException $e){
			$msg = $e->getMessage();
			Page::display(array('module_title' => ADMIN_TITLE, 'main_menu' => 'blank.tpl',
					'back_trace' => $back_trace, 'second_menu' => 'none',
					'back_link' => 'index.php?cmd=show_task_menu_admin',
					'content' => 'general_closure_form_html.tpl', 'task_name' => 'Cierre General', 'notify' => '1',
					'type' => 'failure', 'message' => $msg), 'site_html.tpl');
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('module_title' => ADMIN_TITLE, 'main_menu' => 'blank.tpl',
					'back_trace' => $back_trace, 'second_menu' => 'none',
					'back_link' => 'index.php?cmd=show_task_menu_admin',
					'content' => 'general_closure_form_html.tpl', 'task_name' => 'Cierre General', 'notify' => '1',
					'type' => 'error', 'message' => $msg), 'site_html.tpl');
		}
	}
}
?>