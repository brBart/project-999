<?php
/**
 * Library containing the ShowSalesAndPurchasesStadisticsListCommand base class.
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
 * For displaying the list of products and manufacturers.
 */
require_once('business/product.php');

/**
 * Defines functionality for the showing the sales and purchases stadistics list.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowSalesAndPurchasesStadisticsListCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Herramientas', 'Reportes');
		
		if(is_null($request->getProperty('show_report'))){
			// For displaying the first blank item.
			$product_list = array(array());
			$product_list = array_merge($product_list, ProductDistinctList::getList());
			
			// For displaying the first blank item.
			$manufacturer_list = array(array());
			$manufacturer_list = array_merge($manufacturer_list, ManufacturerDistinctList::getList());
			
			Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'blank.tpl',
					'back_trace' => $back_trace, 'second_menu' => 'none',
					'back_link' => 'index.php?cmd=show_report_menu_pos_admin',
					'content' => 'sales_and_purchases_stadistics_list_form_html.tpl',
					'report_name' => 'Estad&iacute;sticas de Ventas y Compras', 'product_list' => $product_list,
					'manufacturer_list' => $manufacturer_list), 'site_html.tpl');
			return;
		}
		
		$page = (int)$request->getProperty('page');
		$months = (int)$request->getProperty('months');
		$order = $request->getProperty('order');
		
		if($order == 'product'){
			$first = $request->getProperty('product_first');
			$last = $request->getProperty('product_last');
			$list =
				SalesAndPurchasesStadisticsList::getListByProduct($first, $last, $months, $total_pages, $total_items, $page);
		}
		else{
			$first = $request->getProperty('manufacturer_first');
			$last = $request->getProperty('manufacturer_last');
			$list =
				SalesAndPurchasesStadisticsList::getListByManufacturer($first, $last, $months, $total_pages, $total_items, $page);
		}
		
		$first_item = (($page - 1) * ITEMS_PER_PAGE) + 1;
		$last_item = ($page == $total_pages) ? $total_items : $page * ITEMS_PER_PAGE;
		
		$link = 'index.php?cmd=show_sales_and_purchases_stadistics_list&show_report&months=' . $months . '&order=' . $order;
		
		$link .= ($order == 'product') ? '&product_first=' . $first . '&product_last=' . $last :
				'&manufacturer_first=' . $first . '&manufacturer_last=' . $last;
		
		$link .= '&page=';
		
		$previous_link = ($page == 1) ? '' : $link . ($page - 1);
		$next_link = ($page == $total_pages) ? '' : $link . ($page + 1);
		
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => 'index.php?cmd=show_report_menu_pos_admin', 'back_trace' => $back_trace,
				'second_menu' => 'none', 'content' => 'sales_and_purchases_stadistics_list_html.tpl', 'list' => $list,
				'total_items' => $total_items, 'total_pages' => $total_pages, 'page' => $page,
				'first_item' => $first_item, 'last_item' => $last_item, 'previous_link' => $previous_link,
				'next_link' => $next_link, 'months' => $months, 'date' => date('d/m/Y'), 'order' => $order), 'site_html.tpl');
	}
}
?>