<?php
/**
 * Library containing the SetOrganizationDocument base class command.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('presentation/command.php');
/**
 * For displaying the results.
 */
require_once('presentation/page.php');

/**
 * Defines common functionality for the set organization document derived classes.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class SetOrganizationDocumentCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$organization_id = $request->getProperty('organization_id');
		$element_id = $request->getProperty('element_id');
		$organization = $this->getOrganization($organization_id);
		$obj = $helper->getObject((int)$request->getProperty('key'));
		
		try{
			$this->setOrganization($obj, $organization);
		} catch(ValidateException $e){
			$msg = $e->getMessage();
			Page::display(array('success' => '0', 'element_id' => $element_id, 'message' => $msg),
					'validate_xml.tpl');
			return;
		} catch(Exception $e){
			$msg = $e->getMessage();
			Page::display(array('message' => $msg), 'error_xml.tpl');
			return;
		}
			
		Page::display(array('element_id' => $element_id, 'contact' => $organization->getContact()),
				'set_organization_document_xml.tpl');
	}
	
	/**
	 * Returns the desired organization object.
	 * @param string $organizationId
	 * @return Organization
	 */
	abstract protected function getOrganization($organizationId);
	
	/**
	 * Sets the organization to the desired object.
	 * @param Document $document
	 * @param Organization $organization
	 */
	abstract protected function setOrganization(Document $document, Organization $organization = NULL);
}
?>