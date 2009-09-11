<?php
/**
 * Library containing the GetDocumentLastPage class command.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/get_object_last_page.php');

/**
 * Returns the name of the template to use for displaying the document's details.
 * @package Command
 * @author Roberto Oliveros
 */
class GetDocumentLastPageCommand extends GetObjectLastPageCommand{
	/**
	 * Returns the name of the template to use.
	 * @return string
	 */
	protected function getTemplate(){
		return 'document_page_xml.tpl';
	}
}
?>