<?php
/**
 * Library containing the PrintSalesAndPurchasesStadisticsListCommand class.
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
 * Displays the sales ranking list for printing purposes.
 * @package Command
 * @author Roberto Oliveros
 */
class PrintSalesAndPurchasesStadisticsListCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$date = $request->getProperty('date');
		$months = $request->getProperty('months');
		$first = $request->getProperty('first');
		$last = $request->getProperty('last');
		$order = $request->getProperty('order');
		
		if($order == 'product')
			$list =
				SalesAndPurchasesStadisticsList::getListByProduct($first, $last, $date, $months);
		else
			$list =
				SalesAndPurchasesStadisticsList::getListByManufacturer($first, $last, $date, $months);
				
		$months_names = SalesAndPurchasesStadisticsList::buildMonthsNames($date, $months);
		
		Page::display(array('total_items' => count($list), 'date' => $date, 'order' => $order,
				'months' => $months, 'list' => $list, 'months_names' => $months_names),
				'sales_and_purchases_stadistics_list_print_html.tpl');
	}
}
?>