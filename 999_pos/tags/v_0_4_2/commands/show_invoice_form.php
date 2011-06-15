<?php
/**
 * Library containing the ShowInvoiceFormCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/show_cash_register_object_form.php');

/**
 * Command to display an invoice form and indicate an empty list.
 * @package Command
 * @author Roberto Oliveros
 */
class ShowInvoiceFormCommand extends ShowCashRegisterObjectFormCommand{
	/**
	 * Returns an array with the back trace strings to display.
	 * @return array
	 */
	protected function getBackTrace(){
		return array('Inicio', 'Facturaci&oacute;n');
	}
	
	/**
	 * Returns the name of the template to use.
	 * @return string
	 */
	protected function getTemplate(){
		return 'invoice_form_html.tpl';
	}
	
	/**
	 * Return string with the message to display.
	 * @return string
	 */
	protected function getMessage(){
		return 'No hay facturas en esta caja.';
	}
}
?>