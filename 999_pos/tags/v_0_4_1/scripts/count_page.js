/**
 * @fileOverview Library with the CountPage class.
 * @author Roberto Oliveros
 */

/**
 * @class Manages a count's details page.
 * @extends ObjectPage
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {Request} oRequest
 * @param {String} sKey
 * @param {StateMachine} oMachine
 * @param {EventDelegator} oEventDelegator
 */
function CountPage(oSession, oConsole, oRequest, sKey, oMachine, oEventDelegator){
	// Call the parent constructor.
	ObjectPage.call(this, oSession, oConsole, oRequest, sKey, oMachine, oEventDelegator);
}

/**
 * Inherit the Details class methods.
 */
CountPage.prototype = new ObjectPage();

/**
 * Returns the name of the command for getting last page on the server.
 * @returns {String}
 */
CountPage.prototype.getLastPageCmd = function(){
	return 'get_count_last_page';
}

/**
 * Returns the name of the command for getting a page on the server.
 * @returns {String}
 */
CountPage.prototype.getPageCmd = function(){
	return 'get_count_page';
}