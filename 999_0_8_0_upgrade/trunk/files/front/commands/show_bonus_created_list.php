<?php
/**
 * Library containing the ShowBonusCreatedListCommand base class.
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
 * For obtaining the list.
 */
require_once('business/various.php');

/**
 * Defines functionality for the showing the bonus created list.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowBonusCreatedListCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Herramientas', 'Reportes');
		
		if(is_null($request->getProperty('show_reference'))){
			Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'blank.tpl',
					'back_trace' => $back_trace, 'second_menu' => 'none',
					'back_link' => 'index.php?cmd=show_report_menu_pos_admin',
					'content' => 'reference_form_html.tpl', 'reference_type' => LIST_TYPE,
					'reference_name' => 'Ofertas Creadas',
					'reference_cmd' => 'index.php?cmd=show_bonus_created_list&page=1'),
					'site_html.tpl');
			return;
		}
		
		$page = (int)$request->getProperty('page');
		$start_date = $request->getProperty('start_date');
		$end_date = $request->getProperty('end_date');
		
		try{
			$list = BonusList::getCreatedList($start_date, $end_date, $total_pages, $total_items, $page);
		} catch(Exception $e){
			$msg = $e->getMessage();
			
			$back_trace = array('Inicio', 'Herramientas', 'Reportes');
			Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'blank.tpl',
					'back_trace' => $back_trace, 'second_menu' => 'none',
					'reference_type' => LIST_TYPE, 'reference_name' => 'Ofertas Creadas',
					'reference_cmd' => 'index.php?cmd=show_bonus_created_list&page=1',
					'back_link' => 'index.php?cmd=show_report_menu_pos_admin',
					'content' => 'reference_form_html.tpl', 'notify' => '1', 'type' => 'error',
					'message' => $msg, 'start_date' => $start_date, 'end_date' => $end_date),
					'site_html.tpl');
			
			return;
		}
		
		if($total_items > 0){	
			$first_item = (($page - 1) * ITEMS_PER_PAGE) + 1;
			$last_item = ($page == $total_pages) ? $total_items : $page * ITEMS_PER_PAGE;
			
			$link = 'index.php?cmd=show_bonus_created_list&show_reference&page=';
			$dates = '&start_date=' . urlencode($start_date) . '&end_date=' . urlencode($end_date);
			$previous_link = ($page == 1) ? '' : $link . ($page - 1) . $dates;
			$next_link = ($page == $total_pages) ? '' : $link . ($page + 1) . $dates;
			
			Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'back_link.tpl',
					'back_link' => 'index.php?cmd=show_report_menu_pos_admin', 'back_trace' => $back_trace,
					'second_menu' => 'none', 'content' => 'bonus_list_html.tpl', 'list' => $list,
					'total_items' => $total_items, 'total_pages' => $total_pages, 'page' => $page,
					'first_item' => $first_item, 'last_item' => $last_item, 'previous_link' => $previous_link,
					'next_link' => $next_link, 'start_date' => $start_date, 'end_date' => $end_date,
					 'date' => date('d/m/Y'), 'bonus_used' => 0), 'site_html.tpl');
		}
		else {
			$msg = 'No hay ofertas creadas en esas fechas en la base de datos.';
			Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'back_link.tpl',
					'back_link' => 'index.php?cmd=show_report_menu_pos_admin', 'back_trace' => $back_trace,
					'second_menu' => 'none', 'content' => 'none', 'notify' => '1', 'type' => 'info',
					'message' => $msg), 'site_html.tpl');
		}
	}
}
?>