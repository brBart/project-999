<?php
/**
 * Library containing the ShowHomePosCommand class.
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
 * Command to display the POS home page.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowHomePosCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio');
		
		$wday_key = $request->getProperty("wday_key");
		$working_day = $helper->getObject((int)$wday_key);
		
		Page::display(array('module_title' => POS_TITLE, 'back_trace' => $back_trace,
				'content' => 'main_menu_pos_html.tpl', 'date' => $working_day->getDate(),
				'status' => (int)$working_day->isOpen(), 'wday_key' => $wday_key),
				'site_pos_html.tpl');
	}
}
?>