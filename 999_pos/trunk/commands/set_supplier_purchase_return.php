<?php
/**
 * Library containing the SetSupplierPurchaseReturnCommand base class.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/set_organization_document.php');

/**
 * Defines functionality for setting the supplier to the purchase return.
 * @package Command
 * @author Roberto Oliveros
 */
class SetSupplierPurchaseReturnCommand extends SetOrganizationDocumentCommand{
	/**
	 * Returns the desired organization object.
	 * @param string $organizationId
	 * @return Organization
	 */
	protected function getOrganization($organizationId){
		return Supplier::getInstance((int)$organizationId);
	}
	
	/**
	 * Sets the organization to the desired object.
	 * @param Document $document
	 * @param Organization $organization
	 */
	protected function setOrganization(Document $document, Organization $organization = NULL){
		$document->setSupplier($organization);
	}
}
?>