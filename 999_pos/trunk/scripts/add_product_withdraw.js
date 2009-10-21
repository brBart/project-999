/**
 * @fileOverview Library with the AddProductWithdrawCommand base class.
 * @author Roberto Oliveros
 */

/**
 * @class Adds a product to an object on the server.
 * @extends AddProductObjectCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 * @param {String} sKey
 * @param {ObjectPage} oDetails
 * @param {String} sCmd
 */
function AddProductWithdrawCommand(oSession, oConsole, oRequest, sKey, oDetails, sCmd){
	// Call the parent constructor.
	AddProductObjectCommand.call(this, oSession, oConsole, oRequest, sKey, oDetails, sCmd);
}

/**
* Inherit the Sync command class methods.
*/
AddProductWithdrawCommand.prototype = new AddProductObjectCommand();

/**
 * Executes the command. Sends the quantity and bar code.
 */
AddProductWithdrawCommand.prototype.execute = function(){
	 var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmd);
	 str = Url.addUrlParam(str, 'key', this._mKey);
	 str = Url.addUrlParam(str, 'quantity', this._mQuantity.value);
	 str = Url.addUrlParam(str, 'bar_code', this._mBarCode.value);
	 this.sendRequest(str);
}

/**
 * Clean the input elements and refresh the details object with the last page.
 * @param {DocumentElement} xmlDoc
 */
AddProductWithdrawCommand.prototype.displaySuccess = function(xmlDoc){
	// Call parent's method.
	AddProductObjectCommand.prototype.displaySuccess.call(this, xmlDoc);
	StateMachine.setFocus(this._mBarCode);
}