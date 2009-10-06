/**
 * @fileOverview Library with the ProductSuppliers class.
 * @author Roberto Oliveros
 */

/**
 * @class Manages a product's list of suppliers.
 * @extends Details
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 * @param {String} sKey
 * @param {StateMachine} oMachine
 * @param {EventDelegator} oEventDelegator
 */
function ProductSuppliers(oSession, oConsole, oRequest, sKey, oMachine, oEventDelegator){
	// Call the parent constructor.
	Details.call(this, oSession, oConsole, oRequest, sKey, oMachine, oEventDelegator);
	
	/**
	 * Holds the command name on the server.
	 * @type String
	 */
	this._mCmd = 'get_product_suppliers';
}

/**
 * Inherit the Details class methods.
 */
ProductSuppliers.prototype = new Details();

/**
 * Sends the request to the server for fetching new data.
 */
ProductSuppliers.prototype.update = function(){
	var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmd);
	str = Url.addUrlParam(str, 'key', this._mKey);
	this.sendRequest(str);
}