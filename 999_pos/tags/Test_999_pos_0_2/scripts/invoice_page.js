/**
 * @fileOverview Library with the InvoicePage class.
 * @author Roberto Oliveros
 */

/**
 * @class Manages an invoice's details page.
 * @extends ObjectPage
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {Request} oRequest
 * @param {String} sKey
 * @param {StateMachine} oMachine
 * @param {EventDelegator} oEventDelegator
 */
function InvoicePage(oSession, oConsole, oRequest, sKey, oMachine, oEventDelegator){
	// Call the parent constructor.
	ObjectPage.call(this, oSession, oConsole, oRequest, sKey, oMachine, oEventDelegator);
}

/**
 * Inherit the Details class methods.
 */
InvoicePage.prototype = new ObjectPage();

/**
 * Returns the name of the command for getting last page on the server.
 * @returns {String}
 */
InvoicePage.prototype.getLastPageCmd = function(){
	return 'get_invoice_last_page';
}

/**
 * Returns the name of the command for getting a page on the server.
 * @returns {String}
 */
InvoicePage.prototype.getPageCmd = function(){
	return 'get_invoice_page';
}