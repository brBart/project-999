<?php
/**
 * Library containing the ShowNearExpirationLotListCommand base class.
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
require_once('business/product.php');

/**
 * Defines functionality for the showing the near expiration lot list.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowNearExpirationLotListCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Herramientas', 'Reportes');
		
		if(is_null($request->getProperty('show_report'))){
			Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'blank.tpl',
					'back_trace' => $back_trace, 'second_menu' => 'none',
					'back_link' => 'index.php?cmd=show_report_menu_inventory', 'date' => date('d/m/Y'),
					'content' => 'near_expiration_lot_list_form_html.tpl', 'report_name' => 'Lotes Proximos a Vencer'),
					'site_html.tpl');
			return;
		}
		
		$page = (int)$request->getProperty('page');
		$days = (int)$request->getProperty('days');
		$date =  $request->getProperty('date');
		$list = NearExpirationLotList::getList($date, $days, $total_pages, $total_items, $page);
		
		if($total_items > 0){	
			$first_item = (($page - 1) * ITEMS_PER_PAGE) + 1;
			$last_item = ($page == $total_pages) ? $total_items : $page * ITEMS_PER_PAGE;
			
			$link = 'index.php?cmd=show_near_expiration_lot_list&show_report&days=' . $days . '&date=' . $date . '&page=';
			$previous_link = ($page == 1) ? '' : $link . ($page - 1);
			$next_link = ($page == $total_pages) ? '' : $link . ($page + 1);
			
			Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'back_link.tpl',
					'back_link' => 'index.php?cmd=show_report_menu_inventory', 'back_trace' => $back_trace,
					'second_menu' => 'none', 'content' => 'near_expiration_lot_list_html.tpl', 'list' => $list,
					'total_items' => $total_items, 'total_pages' => $total_pages, 'page' => $page,
					'first_item' => $first_item, 'last_item' => $last_item, 'previous_link' => $previous_link,
					'next_link' => $next_link, 'days' => $days, 'date' => $date), 'site_html.tpl');
		}
		else {
			$msg = 'No hay lotes a vencer en esos proximos dias.';
			Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'back_link.tpl',
					'back_link' => 'index.php?cmd=show_report_menu_inventory', 'back_trace' => $back_trace,
					'second_menu' => 'none', 'content' => 'none', 'notify' => '1', 'type' => 'info',
					'message' => $msg), 'site_html.tpl');
		}
	}
}
?>