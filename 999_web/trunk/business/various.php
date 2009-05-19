<?php
/**
 * Library with utility classes for multiple purposes.
 * @package Various
 * @author Roberto Oliveros
 */

/**
 * For validation purposes.
 */
require_once('business/validator.php');
/**
 * For accessing the database.
 */
require_once('data/various_dam.php');

/**
 * Class to perform a closing event in the system's data.
 * @package Various
 * @author Roberto Oliveros
 */
class ClosingEvent{
	/**
	 * Makes a closing on the system's data leaving only the provided months of information.
	 *
	 * @param integer $months
	 */
	static public function apply($months){
		Number::validatePositiveInteger($months, 'Cantidad de meses inv&aacute;lida.');
		ClosingEventDAM::apply($months);
	}
}
?>