<?php
/**
 * Library containing the GetShiftListCommand class.
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
 * For obtaining the shift list.
 */
require_once('business/list.php');

/**
 * Defines functionality for obtaining the whole shifts list.
 * @package Command
 * @author Roberto Oliveros
 */
class GetShiftListCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$list = ShiftList::getList();
		Page::display(array('list' => $list), 'shift_list_xml.tpl');
	}
}
?>