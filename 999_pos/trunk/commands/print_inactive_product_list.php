<?php
/**
 * Library containing the PrintInactiveProductListCommand class.
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
 * Displays the inactive product list for printing purposes.
 * @package Command
 * @author Roberto Oliveros
 */
class PrintInactiveProductListCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$days = (int)$request->getProperty('days');
		$date = $request->getProperty('date');
		$list = InactiveProductList::getList($date, $days);
		
		Page::display(array('total_items' => count($list), 'days' => $days, 'date' => date('d/m/Y'),
				'date' => $date, 'list' => $list), 'inactive_product_list_print_html.tpl');
	}
}
?>