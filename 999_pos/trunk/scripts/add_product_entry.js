/**
 * @fileOverview Library with the AddProductEntryCommand class.
 * @author Roberto Oliveros
 */

/**
 * @class Adds a product to an entry type document on the server.
 * @extends AddProductObjectCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 * @param {String} sKey
 * @param {ObjectPage} oDetails
 * @param {String} sCmd
 */
function AddProductEntryCommand(oSession, oConsole, oRequest, sKey, oDetails, sCmd){
	// Call the parent constructor.
	AddProductObjectCommand.call(this, oSession, oConsole, oRequest, sKey, oDetails, sCmd);
	
	/**
	 * Holds a reference to the price input element.
	 * @type HtmlElement
	 */
	this._mPrice = null;
	
	/**
	 * Holds a reference to the expiration date input element.
	 * @type HtmlElement
	 */
	this._mExpirationDate = null;
}

/**
* Inherit the Sync command class methods.
*/
AddProductEntryCommand.prototype = new AddProductObjectCommand();

/**
 * Sets the input elements from where the values will be read.
 * @param {String} sBarCode
 * @param {String} sQuantity
 * @param {String} sProductName
 * @param {String} sPrice The id of the price input element.
 * @param {String} sExpirationDate The id of the expiration date input element.
 */
AddProductEntryCommand.prototype.init = function(sBarCode, sQuantity, sProductName, sPrice, sExpirationDate){
	// Call parent's init function first.
	AddProductObjectCommand.prototype.init.call(this, sBarCode, sQuantity, sProductName);
	
	this._mPrice = document.getElementById(sPrice);
	this._mExpirationDate = document.getElementById(sExpirationDate);
}

/**
 * Executes the command. Sends the quantity, price, expiration date and bar code.
 */
AddProductEntryCommand.prototype.execute = function(sCmd){
	 if(sCmd == '')
			this._mConsole.displayError('Interno: Argumento sCmd inv&aacute;lido.');
	 else{
		 var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmd);
		 str = Url.addUrlParam(str, 'key', this._mKey);
		 str = Url.addUrlParam(str, 'quantity', this._mQuantity.value);
		 str = Url.addUrlParam(str, 'price', this._mPrice.value);
		 str = Url.addUrlParam(str, 'expiration_date', this._mExpirationDate.value);
		 str = Url.addUrlParam(str, 'bar_code', this._mBarCode.value);
		 this.sendRequest(str);
	 }
}

/**
 * Clean the price and expiration date input elements and invokes the parent's method.
 * @param {DocumentElement} xmlDoc
 */
AddProductEntryCommand.prototype.displaySuccess = function(xmlDoc){
	// Clear 2 possibilities.
	this._mConsole.cleanFailure('price');
	this._mConsole.cleanFailure('expiration_date');
	
	// Clear elements.
	this._mPrice.value = '';
	this._mExpirationDate.value = '';
	
	// Call parent's method.
	AddProductObjectCommand.prototype.displaySuccess.call(this, xmlDoc);
}

/**
 * Clean the price and expiration date input elements and invokes the parent's method.
 * @param {DocumentElement} xmlDoc
 * @param {String} strMsg
 */
AddProductEntryCommand.prototype.displayFailure = function(xmlDoc, strMsg){	
	// Must clean the 2 possibilities in case a failure has been already been display.
	this._mConsole.cleanFailure('price');
	this._mConsole.cleanFailure('expiration_date');
	
	// Call parent's method.
	AddProductObjectCommand.prototype.displayFailure.call(this, xmlDoc, strMsg);
}