<?php
/**
 * Library containing the ShowProductListCommand class.
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
require_once('business/list.php');

/**
 * Defines functionality for showing the product list.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowProductListCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$page = (int)$request->getProperty('page');
		$list = ProductList::getList($total_pages, $total_items, $page);
		
		if($total_items > 0){
			// In case last item was deleted on the last page.
			if($page > $total_pages){
				$page = $page - 1;
				$list = ProductList::getList($total_pages, $total_items, $page);
			}
			
			$first_item = (($page - 1) * PRODUCTS_PER_PAGE) + 1;
			$last_item = ($page == $total_pages) ? $total_items : $page * PRODUCTS_PER_PAGE;
			
			// For back link purposes.
			$actual_cmd = $request->getProperty('cmd');
			
			$link = 'index.php?cmd=' . $actual_cmd . '&page=';
			$previous_link = ($page == 1) ? '' : $link . ($page - 1);
			$next_link = ($page == $total_pages) ? '' : $link . ($page + 1);
			
			$back_trace = array('Inicio', 'Mantenimiento', 'Productos');
			Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'back_link.tpl',
					'back_link' => 'index.php?cmd=show_product_menu', 'back_trace' => $back_trace,
					'second_menu' => 'none', 'content' => 'product_list_html.tpl', 'list' => $list,
					'total_items' => $total_items, 'total_pages' => $total_pages, 'page' => $page,
					'first_item' => $first_item, 'last_item' => $last_item, 'previous_link' => $previous_link,
					'next_link' => $next_link, 'item_link' => 'index.php?cmd=get_product_by_id&id=',
					'actual_cmd' => $actual_cmd), 'site_html.tpl');
		}
		else{
			$back_trace = array('Inicio', 'Mantenimiento', 'Productos');
			$msg = 'No hay productos en la base de datos.';
			Page::display(array('module_title' => INVENTORY_TITLE, 'main_menu' => 'back_link.tpl',
					'back_link' => 'index.php?cmd=show_product_menu', 'back_trace' => $back_trace,
					'second_menu' => 'none', 'content' => 'none', 'notify' => '1', 'type' => 'info',
					'message' => $msg), 'site_html.tpl');
		}
	}
}
?>