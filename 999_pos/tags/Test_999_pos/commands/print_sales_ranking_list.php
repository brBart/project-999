<?php
/**
 * Library containing the PrintSalesRankingListCommand class.
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
class PrintSalesRankingListCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$start_date = $request->getProperty('start_date');
		$end_date = $request->getProperty('end_date');
		$list = SalesRankingList::getList($start_date, $end_date);
		
		Page::display(array('total_items' => count($list), 'start_date' => $start_date,
				'end_date' => $end_date, 'list' => $list), 'sales_ranking_list_print_html.tpl');
	}
}
?>