<?php
/**
 * Library containing the PrintInStockListCommand class.
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
 * Displays the products with stock for printing purposes.
 * @package Command
 * @author Roberto Oliveros
 */
class PrintInStockListCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$list = InStockList::getList($total);
		
		Page::display(array('total_items' => count($list),
				'date' => date('d/m/Y'), 'total' => $total, 'list' => $list),
				'product_stock_list_print_html.tpl');
	}
}
?>