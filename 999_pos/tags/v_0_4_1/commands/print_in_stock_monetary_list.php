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
class PrintInStockMonetaryListCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		if(AccessManager::isAllowed($helper->getUser(), 'product_stock_monetary_report', 'read')){
			$list = InStockList::getList(true, $total);
		
			Page::display(array('total_items' => count($list),
					'date' => date('d/m/Y'), 'total' => $total, 'list' => $list),
					'product_stock_monetary_list_print_html.tpl');
		}
		else
			Page::display(array('notify' => '1', 'type' => 'error', 'message' => 'Insuficientes privilegios.'),
					'product_stock_monetary_list_print_html.tpl');
	}
}
?>