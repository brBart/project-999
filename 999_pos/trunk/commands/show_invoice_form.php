<?php
/**
 * Library containing the ShowInvoiceFormCommand class.
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
 * Command to display the cash register status, an invoice form and indicate an empty
 * list.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowInvoiceFormCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$back_trace = array('Inicio', 'Facturaci&oacute;n');
		
		// Sorry, bad practice necessary.
		$working_day = $helper->getWorkingDay();
		$cash_register = $helper->getObject((int)$request->getProperty('register_key'));
		$shift = $cash_register->getShift();
		$msg = 'No hay facturas en esta caja.';
		
		Page::display(array('module_title' => POS_TITLE, 'back_trace' => $back_trace,
				'content' => 'invoice_form_html.tpl', 'cash_register_id' => $cash_register->getId(),
				'date' => $working_day->getDate(), 'status' => '1',
				'shift' => $shift->getName() . ', ' . $shift->getTimeTable(),
				'cash_register_status' => (int)$cash_register->isOpen(), 'notify' => '1',
				'type' => 'info', 'message' => $msg,), 'site_pos_html.tpl');
	}
}
?>