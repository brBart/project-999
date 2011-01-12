<?php
/**
 * Library containing the GetPaymentCardTypeListCommand class.
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
 * For obtaining the payment card brand list.
 */
require_once('business/list.php');

/**
 * Defines functionality for obtaining the whole shifts list.
 * @package Command
 * @author Roberto Oliveros
 */
class GetPaymentCardBrandListCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$list = PaymentCardBrandList::getList();
		Page::display(array('list' => $list), 'payment_card_brand_list_xml.tpl');
	}
}
?>