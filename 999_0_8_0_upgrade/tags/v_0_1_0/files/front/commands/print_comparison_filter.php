<?php
/**
 * Library containing the PrintComparisonFilterCommand class.
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
 * Displays the filtered comparison data for printing purposes.
 * @package Command
 * @author Roberto Oliveros
 */
class PrintComparisonFilterCommand extends PrintObjectCommand{
	/**
	 * Display the object.
	 * @param variant $obj
	 * @param array $details
	 */
	protected function displayObject($obj, $details){		
		$user = $obj->getUser();
		$general = $obj->isGeneral() ? 'Si' : 'No';
		
		switch ($obj->getFilterType()){
			case 0:
				$filter_name = 'Sobrantes';
				break;
			
			case 1:
				$filter_name = 'Faltantes';
				break;
				
			case 2:
				$filter_name = 'Sobrantes y Faltantes';
				break;
		}
		
		Page::display(array('filter_date_time' => $obj->getFilterDateTime(), 'filter_name' => $filter_name,
				'include_prices' => (int)$obj->includePrices(), 'id' => $obj->getId(), 'username' => $user->getUserName(),
				'price_total' => $obj->getPriceTotal(), 'date_time' => $obj->getDateTime(), 'reason' => $obj->getReason(),
				'general' => $general, 'physical_total' => $obj->getPhysicalTotal(), 'system_total' => $obj->getSystemTotal(),
				'total_diference' => $obj->getTotalDiference(), 'total_items' => count($details),
				'details' => $details), 'comparison_filter_print_html.tpl');
	}
}
?>