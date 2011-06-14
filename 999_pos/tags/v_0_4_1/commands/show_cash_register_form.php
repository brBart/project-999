<?php
/**
 * Library containing the ShowCashRegisterFormCommand class.
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
 * Command with functionaltity to display the cash register form.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowCashRegisterFormCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Caja Opciones');
		
		// Sorry, bad practice necessary.
		$working_day = $helper->getWorkingDay();
		$cash_register = $helper->getObject((int)$request->getProperty('register_key'));
		$shift = $cash_register->getShift();
		
		Page::display(array('module_title' => POS_TITLE, 'back_trace' => $back_trace,
				'content' => 'cash_register_form_html.tpl',
				'cash_register_id' => $cash_register->getId(), 'date' => $working_day->getDate(),
				'shift' => $shift->getName() . ', ' . $shift->getTimeTable(),
				'cash_register_status' => (int)$cash_register->isOpen()), 'site_pos_html.tpl');
	}
}
?>