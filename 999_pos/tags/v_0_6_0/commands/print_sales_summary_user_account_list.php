<?php
/**
 * Library containing the PrintSalesSummaryUserAccountListCommand class.
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
 * Displays the sales summary of user accounts for printing purposes.
 * @package Command
 * @author Roberto Oliveros
 */
class PrintSalesSummaryUserAccountListCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		if(AccessManager::isAllowed($helper->getUser(), 'sales_summary_user_account_report', 'read')){
			$start_date = $request->getProperty('start_date');
			$end_date = $request->getProperty('end_date');
			$list = SalesSummaryList::getListByUserAccount($start_date, $end_date, $total);
			
			Page::display(array('total_items' => count($list), 'start_date' => $start_date,
					'end_date' => $end_date, 'list' => $list, 'total' => $total),
					'sales_summary_user_account_list_print_html.tpl');
		} 
		else
			Page::display(array('notify' => '1', 'type' => 'error', 'message' => 'Insuficientes privilegios.'),
					'sales_summary_user_account_list_print_html.tpl');
	}
}
?>