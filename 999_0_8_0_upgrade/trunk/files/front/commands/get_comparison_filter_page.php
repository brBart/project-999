<?php
/**
 * Library containing the GetComparisonFilterPageCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_comparison_page.php');

/**
 * Defines the extra parameters for the filtered comparison.
 * @package Command
 * @author Roberto Oliveros
 */
class GetComparisonFilterPageCommand extends GetComparisonPageCommand{
	/**
	 * Returns the params to display for the object.
	 * @param variant $obj
	 */
	protected function getObjectParams($obj){
		return array('price_total' => $obj->getPriceTotal(), 'include_prices' => (int)$obj->includePrices());
	}
}
?>