<?php
/**
 * Library containing the PrintKardexCommand class.
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
 * Displays the receipt data for printing purposes.
 * @package Command
 * @author Roberto Oliveros
 */
class PrintKardexCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$product = $helper->getObject((int)$request->getProperty('key'));
		$kardex = Kardex::showPage($product, $balance);
		
		Page::display(array('id' => $product->getId(), 'name' => $product->getName(),
				'bar_code' => $product->getBarCode(), 'balance' => $balance,
				'total_items' => count($kardex), 'kardex' => $kardex), 'kardex_print_html.tpl');
	}
}
?>