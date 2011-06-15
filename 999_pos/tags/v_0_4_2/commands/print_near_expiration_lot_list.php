<?php
/**
 * Library containing the PrintNearExpirationLotListCommand class.
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
 * Displays the near expiration lot list for printing purposes.
 * @package Command
 * @author Roberto Oliveros
 */
class PrintNearExpirationLotListCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$days = (int)$request->getProperty('days');
		$date = $request->getProperty('date');
		$list = NearExpirationLotList::getList($date, $days);
		
		Page::display(array('total_items' => count($list), 'days' => $days,
				'date' => $date, 'list' => $list), 'near_expiration_lot_list_print_html.tpl');
	}
}
?>