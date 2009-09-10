<?php
/**
 * Library containing the SetSupplierDocument class command.
 * @package Command
 * @author Roberto Oliveros
 */

/**
 * Base class.
 */
require_once('commands/set_agent_document.php');
/**
 * For the supplier class reference.
 */
require_once('business/agent.php');

/**
 * Defines functionality for setting the document's supplier.
 * @package Command
 * @author Roberto Oliveros
 */
class SetSupplierDocumentCommand extends SetAgentDocumentCommand{
	/**
	 * Returns the desired agent object.
	 * @param string $agentId
	 * @return Agent
	 */
	protected function getAgent($agentId){
		return Supplier::getInstance((int)$agentId);
	}
	
	/**
	 * Set the desired property on the object.
	 * @param Document $document
	 * @param Agent $agent
	 */
	protected function setAgent(Document $document, Agent $agent = NULL){
		$document->setSupplier($agent);
	}
}
?>