/**
 * Library with the document page class.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 * @param Session oSession
 * @param Console oConsole
 * @param Request oRequest
 * @param string sKey
 * @param StateMachine oMachine
 */
function DocumentPage(oSession, oConsole, oRequest, sKey, oMachine){
	// Call the parent constructor.
	ObjectPage.call(this, oSession, oConsole, oRequest, sKey, oMachine);
}

/**
* Inherit the Details class methods.
*/
DocumentPage.prototype = new ObjectPage();

/**
 * Returns the name of the last page command on the server.
 * @return string
 */
DocumentPage.prototype.getLastPageCmd = function(){
	return 'get_document_last_page';
}

/**
* Returns the name of the page command on the server.
* @return string
*/
DocumentPage.prototype.getPageCmd = function(){
	return 'get_document_page';
}