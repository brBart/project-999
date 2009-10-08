<?php
/**
 * Library containing the SetSupplierDocumentCommand class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/set_organization_document.php');
/**
 * For the supplier class reference.
 */
require_once('business/agent.php');

/**
 * Defines functionality for setting the document's supplier.
 * @package Command
 * @author Roberto Oliveros
 */
class SetSupplierDocumentCommand extends SetOrganizationDocumentCommand{
	/**
	 * Returns the desired organization object.
	 * @param string $organizationId
	 * @return Organization
	 */
	protected function getOrganization($organizationId){
		return Supplier::getInstance((int)$organizationId);
	}
	
	/**
	 * Set the desired property on the object.
	 * @param Document $document
	 * @param Organization $organization
	 */
	protected function setOrganization(Document $document, Organization $organization = NULL){
		$document->setSupplier($organization);
	}
}
?>