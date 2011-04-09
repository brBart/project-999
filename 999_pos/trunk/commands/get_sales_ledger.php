<?php
/**
 * Library containing the GetSalesLedgerCommand class.
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
 * For obtaining the ledger.
 */
require_once('business/various.php');

/**
 * Command to display the sales ledger form.
 * @package Command
 * @author Roberto Oliveros
 */
class GetSalesLedgerCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Herramientas', 'Tareas');
		
		if(is_null($request->getProperty('get_sales_ledger'))){
			Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'blank.tpl',
					'back_trace' => $back_trace, 'second_menu' => 'none',
					'back_link' => 'index.php?cmd=show_task_menu_pos_admin', 'status' => '0',
					'content' => 'sales_ledger_form_html.tpl', 'task_name' => 'Libro de Ventas'),
					'site_html.tpl');
			return;
		}
		
		try{
			$file_url = BackupEvent::apply();
			$file_url = '..' . BACKUP_DIR_NAME . $file_url;
			
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