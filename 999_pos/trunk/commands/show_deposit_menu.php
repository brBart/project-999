<?php
/**
 * Library containing the ShowDepositMenuCommand class.
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
 * Command to display the deposit menu on the POS Admin module.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowDepositMenuCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Caja', 'Depositos');
		
		// For displaying the first blank item.
		$list = array(array());
		$list = array_merge($list, BankList::getList());
		
		Page::display(array('module_title' => POS_ADMIN_TITLE, 'main_menu' => 'main_menu_pos_admin_html.tpl',
				'back_trace' => $back_trace, 'second_menu' => 'cash_register_menu_html.tpl',
				'bank_list' => $list, 'content' => 'deposit_menu_html.tpl'), 'site_html.tpl');
	}
}
?>