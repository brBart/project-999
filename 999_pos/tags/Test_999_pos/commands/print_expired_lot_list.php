<?php
/**
 * Library containing the PrintExpiredLotListCommand class.
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
 * Displays the expired lot list for printing purposes.
 * @package Command
 * @author Roberto Oliveros
 */
class PrintExpiredLotListCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$date = $request->getProperty('date');
		$list = ExpiredLotList::getList($date);
		
		Page::display(array('total_items' => count($list),
				'date' => $date, 'list' => $list), 'expired_lot_list_print_html.tpl');
	}
}
?>