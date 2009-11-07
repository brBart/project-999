<?php
/**
 * Library containing the GetCountLastPageCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_object_last_page.php');

/**
 * Returns the name of the template to use for displaying the count's details.
 * @package Command
 * @author Roberto Oliveros
 */
class GetCountLastPageCommand extends GetObjectLastPageCommand{
	/**
	 * Returns the name of the template to use.
	 * @return string
	 */
	protected function getTemplate(){
		return 'count_page_xml.tpl';
	}
}
?>