<?php
/**
 * Library containing the GetDocumentDetails class command.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('presentation/get_object_details.php');

/**
 * Returns the name of the template to use for displaying the document's details.
 * @package Command
 * @author Roberto Oliveros
 */
class GetDocumentDetailsCommand extends Command{
	/**
	 * Returns the name of the template to use.
	 * @return string
	 */
	protected function getTemplate(){
		return 'document_details_xml.tpl';
	}
}
?>