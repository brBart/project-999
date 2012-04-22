<?php
/**
 * Library containing the PrintComparisonFilterCountingTemplateCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/print_object.php');
/**
 * For displaying the results.
 */
require_once('presentation/page.php');

/**
 * Displays the filtered comparison data for printing on a counting template.
 * @package Command
 * @author Roberto Oliveros
 */
class PrintComparisonFilterCountingTemplateCommand extends PrintObjectCommand{
	/**
	 * Display the object.
	 * @param variant $obj
	 * @param array $details
	 */
	protected function displayObject($obj, $details){
		Page::display(array('date' => date('d/m/Y'), 'order_by' => 'Nombre',
				'details' => $details, 'total_items' => count($details)), 'counting_template_print_html.tpl');
	}
}
?>