<?php
/**
 * Library containing the GetDepositDetailsCommand class.
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
 * For displaying the object details.
 */
require_once('business/itemized.php');

/**
 * Defines functionality for obtaining a deposit's details.
 * @package Command
 * @author Roberto Oliveros
 */
class GetDepositDetailsCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$deposit = $helper->getObject((int)$request->getProperty('key'));
		$details = DetailsPrinter::showPage($deposit);
		Page::display(array('details' => $details, 'total' => $deposit->getTotal(),
				'total_items' => count($details)), 'deposit_details_xml.tpl');
	}
}
?>