<?php
/**
 * Library containing the ShowDepositFormCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/show_cash_register_object_form.php');

/**
 * Command to display a deposit form and indicate an empty list.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowDepositFormCommand extends ShowCashRegisterObjectFormCommand{
	/**
	 * Returns an array with the back trace strings to display.
	 * @return array
	 */
	protected function getBackTrace(){
		return array('Inicio', 'Depositos');
	}
	
	/**
	 * Returns the name of the template to use.
	 * @return string
	 */
	protected function getTemplate(){
		return 'deposit_form_html.tpl';
	}
	
	/**
	 * Return string with the message to display.
	 * @return string
	 */
	protected function getMessage(){
		return 'No hay depositos en esta caja.';
	}
}
?>