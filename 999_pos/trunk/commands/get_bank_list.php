<?php
/**
 * Library containing the GetBankListCommand class.
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
 * For obtaining the bank list.
 */
require_once('business/list.php');

/**
 * Defines functionality for obtaining the whole banks list.
 * @package Command
 * @author Roberto Oliveros
 */
class GetBankListCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$list = BankList::getList();
		Page::display(array('list' => $list), 'bank_list_xml.tpl');
	}
}
?>