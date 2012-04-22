/**
 * @fileOverview Library with the ComparisonFilterPage class.
 * @author Roberto Oliveros
 */

/**
 * @class Manages a filtered comparison's details page.
 * @extends ObjectPage
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {Request} oRequest
 * @param {String} sKey
 * @param {StateMachine} oMachine
 * @param {EventDelegator} oEventDelegator
 */
function ComparisonFilterPage(oSession, oConsole, oRequest, sKey, oMachine, oEventDelegator){
	// Call the parent constructor.
	ObjectPage.call(this, oSession, oConsole, oRequest, sKey, oMachine, oEventDelegator);
}

/**
 * Inherit the Details class methods.
 */
ComparisonFilterPage.prototype = new ObjectPage();

/**
 * Returns the name of the command for getting last page on the server.
 * @returns {String}
 */
ComparisonFilterPage.prototype.getLastPageCmd = function(){
	return 'get_comparison_filter_last_page';
}

/**
 * Returns the name of the command for getting a page on the server.
 * @returns {String}
 */
ComparisonFilterPage.prototype.getPageCmd = function(){
	return 'get_comparison_filter_page';
}