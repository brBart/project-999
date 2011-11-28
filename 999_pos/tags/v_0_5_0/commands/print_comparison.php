<?php
/**
 * Library containing the PrintComparisonCommand class.
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
 * Displays the comparison data for printing purposes.
 * @package Command
 * @author Roberto Oliveros
 */
class PrintComparisonCommand extends PrintObjectCommand{
	/**
	 * Display the object.
	 * @param variant $obj
	 * @param array $details
	 */
	protected function displayObject($obj, $details){		
		$user = $obj->getUser();
		$general = $obj->isGeneral() ? 'Si' : 'No';
		
		Page::display(array('id' => $obj->getId(), 'username' => $user->getUserName(),
				'date_time' => $obj->getDateTime(), 'reason' => $obj->getReason(), 'general' => $general,
				'physical_total' => $obj->getPhysicalTotal(), 'system_total' => $obj->getSystemTotal(),
				'total_diference' => $obj->getTotalDiference(), 'total_items' => count($details),
				'details' => $details), 'comparison_print_html.tpl');
	}
}
?>