<?php
/**
 * Library containing the PrintPurchasesSummaryProductListCommand class.
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
 * Displays the purchases summary of products for printing purposes.
 * @package Command
 * @author Roberto Oliveros
 */
class PrintPurchasesSummaryProductListCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		if(AccessManager::isAllowed($helper->getUser(), 'purchases_summary_product_report', 'read')){
			$start_date = $request->getProperty('start_date');
			$end_date = $request->getProperty('end_date');
			$list = PurchasesSummaryList::getListByProduct($start_date, $end_date, $total);
			
			Page::display(array('total_items' => count($list), 'start_date' => $start_date, 'date' => date('d/m/Y'),
					'end_date' => $end_date, 'list' => $list, 'total' => $total),
					'purchases_summary_product_list_print_html.tpl');
		} 
		else
			Page::display(array('notify' => '1', 'type' => 'error', 'message' => 'Insuficientes privilegios.'),
					'purchases_summary_product_list_print_html.tpl');
	}
}
?>