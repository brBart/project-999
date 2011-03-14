<?php
/**
 * Library containing the PrintCountCommand class.
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
 * Displays the count data for printing purposes.
 * @package Command
 * @author Roberto Oliveros
 */
class PrintCountCommand extends PrintObjectCommand{
	/**
	 * Display the object.
	 * @param variant $obj
	 * @param array $details
	 */
	protected function displayObject($obj, $details){		
		$user = $obj->getUser();
		
		Page::display(array('id' => $obj->getId(), 'username' => $user->getUserName(),
				'date_time' => $obj->getDateTime(), 'reason' => $obj->getReason(),
				'total' => $obj->getTotal(), 'total_items' => count($details), 'details' => $details),
				'count_print_html.tpl');
	}
}
?>