/**
 * @fileOverview Library with the KardexPage class.
 * @author Roberto Oliveros
 */

/**
 * @class Manages a product's kardex details page.
 * @extends ObjectPage
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {Request} oRequest
 * @param {String} sKey
 * @param {StateMachine} oMachine
 * @param {EventDelegator} oEventDelegator
 */
function KardexPage(oSession, oConsole, oRequest, sKey, oMachine, oEventDelegator){
	// Call the parent constructor.
	ObjectPage.call(this, oSession, oConsole, oRequest, sKey, oMachine, oEventDelegator);
}

/**
 * Inherit the Details class methods.
 */
KardexPage.prototype = new ObjectPage();

/**
 * Returns the name of the command for getting last page on the server.
 * @returns {String}
 */
KardexPage.prototype.getLastPageCmd = function(){
	return 'show_kardex_last_page';
}

/**
 * Returns the name of the command for getting a page on the server.
 * @returns {String}
 */
KardexPage.prototype.getPageCmd = function(){
	return 'show_kardex_page';
}