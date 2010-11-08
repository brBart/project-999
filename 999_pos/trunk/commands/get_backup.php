<?php
/**
 * Library containing the GetBackupCommand class.
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
 * Command to display the backup form.
 * @package Command
 * @author Roberto Oliveros
 */
class GetBackupCommand extends Command{
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
					'back_link' => 'index.php?cmd=show_task_menu_admin', 'status' => '0',
					'content' => 'backup_form_html.tpl', 'task_name' => 'Backup'),
					'site_html.tpl');
			return;
		}
		
		try{
			$file_url = BackupEvent::apply();
			$back_trace = array('Inicio');
			$msg = 'El backup se realizo exitosamente.';
			Page::display(array('module_title' => ADMIN_TITLE, 'main_menu' => 'back_link.tpl', 'status' => '1',
					'back_trace' => $back_trace, 'second_menu' => 'none', 'content' => 'backup_form_html.tpl',
					'back_link' => 'index.php?cmd=show_task_menu_admin', 'task_name' => 'Backup',
					'file_url' => $file_url, 'notify' => '1', 'type' => 'success', 'message' => $msg),
					'site_html.tpl');
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('module_title' => ADMIN_TITLE, 'main_menu' => 'blank.tpl',
					'back_trace' => $back_trace, 'second_menu' => 'none',
					'back_link' => 'index.php?cmd=show_task_menu_admin', 'status' => '0',
					'content' => 'backup_form_html.tpl', 'task_name' => 'Backup', 'notify' => '1',
					'type' => 'error', 'message' => $msg), 'site_html.tpl');
		}
	}
}
?>