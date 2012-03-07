<?php
/**
 * Library containing the GetComparisonFilterLastPageCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_comparison_last_page.php');

/**
 * Defines the extra parameters for the filtered comparison.
 * @package Command
 * @author Roberto Oliveros
 */
class GetComparisonFilterLastPageCommand extends GetComparisonLastPageCommand{
	/**
	 * Returns the params to display for the object.
	 * @param variant $obj
	 */
	protected function getObjectParams($obj){
		return array('comparison_filter' => '1', 'price_total' => $obj->getPriceTotal(),
				'include_prices' => (int)$obj->includePrices());
	}
}
?>