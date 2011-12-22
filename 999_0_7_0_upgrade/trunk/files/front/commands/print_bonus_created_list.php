<?php
/**
 * Library containing the PrintBonusCreatedListCommand class.
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
require_once('business/various.php');

/**
 * Displays the bonus created list for printing purposes.
 * @package Command
 * @author Roberto Oliveros
 */
class PrintBonusCreatedListCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$start_date = $request->getProperty('start_date');
		$end_date = $request->getProperty('end_date');
		$list = BonusCreatedList::getList($start_date, $end_date);
		
		Page::display(array('total_items' => count($list), 'start_date' => $start_date,
				'end_date' => $end_date, 'list' => $list, 'date' => date('d/m/Y')),
				'bonus_created_list_print_html.tpl');
	}
}
?>