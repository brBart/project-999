/**
 * @fileOverview Library with the DocumentPage class.
 * @author Roberto Oliveros
 */

/**
 * @class Manages a document's details page.
 * @extends ObjectPage
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {Request} oRequest
 * @param {String} sKey
 * @param {StateMachine} oMachine
 * @param {EventDelegator} oEventDelegator
 */
function DocumentPage(oSession, oConsole, oRequest, sKey, oMachine, oEventDelegator){
	// Call the parent constructor.
	ObjectPage.call(this, oSession, oConsole, oRequest, sKey, oMachine, oEventDelegator);
}

/**
 * Inherit the Details class methods.
 */
DocumentPage.prototype = new ObjectPage();

/**
 * Returns the name of the command for getting last page on the server.
 * @returns {String}
 */
DocumentPage.prototype.getLastPageCmd = function(){
	return 'get_document_last_page';
}

/**
 * Returns the name of the command for getting a page on the server.
 * @returns {String}
 */
DocumentPage.prototype.getPageCmd = function(){
	return 'get_document_page';
}