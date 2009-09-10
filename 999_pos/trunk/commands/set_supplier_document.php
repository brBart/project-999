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
	 * @param Agent $agent
	 * @param Document $document
	 */
	protected function setAgent(Agent $agent, Document $document){
		$document->setSupplier($agent);
	}
}
?>