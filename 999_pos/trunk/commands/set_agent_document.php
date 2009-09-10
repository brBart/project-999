<?php
/**
 * Library containing the SetAgentDocument base class command.
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
 * Defines common functionality for the set agent document derived classes.
 * @package Command
 * @author Roberto Oliveros
 */
abstract class SetAgentDocumentCommand extends Command{
	/**
	 * Execute the command.
	 * @param Request $request
	 * @param SessionHelper $helper
	 */
	public function execute(Request $request, SessionHelper $helper){
		$agent_id = $request->getProperty('agent_id');
		$element_id = $request->getProperty('element_id');
		$agent = $this->getAgent($agent_id);
		$obj = $helper->getObject((int)$request->getProperty('key'));
		
		try{
			$this->setAgent($agent, $obj);
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
			
		Page::display(array('contact' => $agent->getContact()), 'set_agent_document_xml.tpl');
	}
	
	/**
	 * Returns the desired agent object.
	 * @param string $agentId
	 * @return Agent
	 */
	abstract protected function getAgent($agentId);
	
	/**
	 * Sets the agent to the desired object.
	 * @param Agent $agent
	 * @param Document $document
	 */
	abstract protected function setAgent(Agent $agent, Document $document);
}
?>