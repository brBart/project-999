/**
 * @fileOverview Library with the ObjectDetails class.
 * @author Roberto Oliveros
 */

/**
 * @class Manages an object's list of details.
 * @extends Details
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 * @param {String} sKey
 * @param {StateMachine} oMachine
 * @param {EventDelegator} oEventDelegator
 * @param {String} sCmd
 */
function ObjectDetails(oSession, oConsole, oRequest, sKey, oMachine, oEventDelegator, sCmd){
	// Call the parent constructor.
	Details.call(this, oSession, oConsole, oRequest, sKey, oMachine, oEventDelegator);
	
	/**
	 * Holds the command name on the server.
	 * @type String
	 */
	this._mCmd = sCmd;
}

/**
 * Inherit the Details class methods.
 */
ObjectDetails.prototype = new Details();

/**
 * Sends the request to the server for fetching new data.
 */
ObjectDetails.prototype.update = function(){
	var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmd);
	str = Url.addUrlParam(str, 'key', this._mKey);
	this.sendRequest(str);
}