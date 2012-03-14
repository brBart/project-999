<?php
/**
 * Library containing the ShowWorkingDayFormCommand class.
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
 * Command with functionaltity to display the working day form.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowWorkingDayFormCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Jornada Opciones');
		
		// Sorry, bad practice necessary.
		$working_day = $helper->getWorkingDay();
		
		Page::display(array('module_title' => POS_TITLE, 'back_trace' => $back_trace,
				'content' => 'working_day_form_html.tpl',
				'date' => $working_day->getDate(),
				'working_day_status' => (int)$working_day->isOpen()), 'site_pos_html.tpl');
	}
}
?>