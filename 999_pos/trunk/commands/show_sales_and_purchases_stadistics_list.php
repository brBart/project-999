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
			$this->builtArrays($months_list, $product_list, $manufacturer_list);
			
			Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'blank.tpl',
					'back_trace' => $back_trace, 'second_menu' => 'none',
					'back_link' => 'index.php?cmd=show_report_menu_pos_admin',
					'content' => 'sales_and_purchases_stadistics_list_form_html.tpl',
					'months_list' => $months_list, 'months' => '6', 'order' => 'product',
					'report_name' => 'Estad&iacute;sticas de Ventas y Compras', 'product_list' => $product_list,
					'manufacturer_list' => $manufacturer_list), 'site_html.tpl');
			return;
		}
		
		$page = (int)$request->getProperty('page');
		$months = (int)$request->getProperty('months');
		$order = $request->getProperty('order');
		$date = date('d/m/Y');
		
		try{
			if($order == 'product'){
				$first = $request->getProperty('product_first');
				$last = $request->getProperty('product_last');
				$list =
					SalesAndPurchasesStadisticsList::getListByProduct($first, $last, $date, $months, $total_pages, $total_items, $page);
			}
			else{
				$first = $request->getProperty('manufacturer_first');
				$last = $request->getProperty('manufacturer_last');
				$list =
					SalesAndPurchasesStadisticsList::getListByManufacturer($first, $last, $date, $months, $total_pages, $total_items, $page);
			}
		}Catch(Exception $e){
			$msg = $e->getMessage();
			
			$this->builtArrays($months_list, $product_list, $manufacturer_list);
			
			$params = ($order == 'product') ? array('product_first' => $first, 'product_last' => $last) :
					array('manufacturer_first' => $first, 'manufacturer_last' => $last);
			
			$params = array_merge($params, array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'blank.tpl',
					'back_trace' => $back_trace, 'second_menu' => 'none',
					'back_link' => 'index.php?cmd=show_report_menu_pos_admin',
					'content' => 'sales_and_purchases_stadistics_list_form_html.tpl',
					'months_list' => $months_list, 'months' => $months, 'order' => $order,
					'report_name' => 'Estad&iacute;sticas de Ventas y Compras', 'product_list' => $product_list,
					'manufacturer_list' => $manufacturer_list, 'notify' => '1', 'type' => 'error', 'message' => $msg));
			
			Page::display($params, 'site_html.tpl');
			return;
		}
		
		$first_item = (($page - 1) * ITEMS_PER_PAGE) + 1;
		$last_item = ($page == $total_pages) ? $total_items : $page * ITEMS_PER_PAGE;
		
		$link = 'index.php?cmd=show_sales_and_purchases_stadistics_list&show_report&months=' . $months . '&order=' . $order;
		
		$link .= ($order == 'product') ? '&product_first=' . $first . '&product_last=' . $last :
				'&manufacturer_first=' . $first . '&manufacturer_last=' . $last;
		
		$link .= '&page=';
		
		$previous_link = ($page == 1) ? '' : $link . ($page - 1);
		$next_link = ($page == $total_pages) ? '' : $link . ($page + 1);
		
		$months_names = $this->buildMonthsNames($months);
		
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'back_link.tpl',
				'back_link' => 'index.php?cmd=show_report_menu_pos_admin', 'back_trace' => $back_trace,
				'second_menu' => 'none', 'content' => 'sales_and_purchases_stadistics_list_html.tpl', 'list' => $list,
				'total_items' => $total_items, 'total_pages' => $total_pages, 'page' => $page,
				'first_item' => $first_item, 'last_item' => $last_item, 'previous_link' => $previous_link,
				'next_link' => $next_link, 'months' => $months, 'date' => $date, 'order' => $order,
				'months_names' => $months_names), 'site_html.tpl');
	}
	
	/**
	 * Builds the main arrays for displaying the form.
	 * 
	 * @param array &$monthsList
	 * @param array &$productList
	 * @param array &$manufacturerList
	 */
	private function builtArrays(&$monthsList, &$productList, &$manufacturerList){
		$monthsList = array('3', '6', '9');
		
		// For displaying the first blank item.
		$productList = array(array());
		$productList = array_merge($productList, ProductDistinctList::getList());
		
		// For displaying the first blank item.
		$manufacturerList = array(array());
		$manufacturerList = array_merge($manufacturerList, ManufacturerDistinctList::getList());
	}
	
	/**
	 * Returns an array with the names of the months.
	 * 
	 * @param int $months
	 */
	private function buildMonthsNames($months){
		$year_months = array('1' => 'Ene',
							'2' => 'Feb',
							'3' => 'Mar',
							'4' => 'Abr',
							'5' => 'May',
							'6' => 'Jun',
							'7' => 'Jul',
							'8' => 'Ago',
							'9' => 'Sep',
							'10' => 'Oct',
							'11' => 'Nov',
							'12' => 'Dic');
		
		$date = new DateTime();
		$date->modify('- ' . $months  . ' month');
		
		$names = array();
		$i = 0;
		
		do{
			$names[] = $year_months[$date->format('n')] . ' ' . $date->format('y');
			$date->modify('+1 month');
			$i++;
		}while($i < $months);
		
		return $names;
	}
}
?>