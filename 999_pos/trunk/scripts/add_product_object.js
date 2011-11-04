/**
 * @fileOverview Library with the AddProductObjectCommand base class.
 * @author Roberto Oliveros
 */

/**
 * @class Adds a product to an object on the server.
 * @extends SyncCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 * @param {String} sKey
 * @param {ObjectPage} oDetails
 * @param {String} sCmd
 */
function AddProductObjectCommand(oSession, oConsole, oRequest, sKey, oDetails, sCmd){
	// Call the parent constructor.
	SyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the key of the session object.
	 * @type String
	 */
	this._mKey = sKey;
	
	/**
	 * Holds a reference to the object which handles the details table element.
	 * @type ObjectPage
	 */
	this._mDetails = oDetails;
	
	/**
	 * Holds the name of the command on the server.
	 * @type String
	 */
	this._mCmd = sCmd;
	
	/**
	 * Holds a reference to the bar code input element.
	 * @type HtmlElement
	 */
	this._mBarCode = null;
	
	/**
	 * Holds a reference to the quantity input element.
	 * @type HtmlElement
	 */
	this._mQuantity = null;
	
	/**
	 * Holds a reference to the product name input element.
	 * @type HtmlElement
	 */
	this._mProductName = null;
}

/**
* Inherit the Sync command class methods.
*/
AddProductObjectCommand.prototype = new SyncCommand();

/**
 * Sets the input elements from where the values will be read.
 * @param {String} sBarCode The id of the bar code input element.
 * @param {String} sQuantity The id of the quantity input element.
 * @param {String} sProductName The id of the product name input element.
 */
AddProductObjectCommand.prototype.init = function(sBarCode, sQuantity, sProductName){
	this._mBarCode = document.getElementById(sBarCode);
	this._mQuantity = document.getElementById(sQuantity);
	this._mProductName = document.getElementById(sProductName);
}

/**
 * Clean the input elements and refresh the details object with the last page.
 * @param {DocumentElement} xmlDoc
 */
AddProductObjectCommand.prototype.displaySuccess = function(xmlDoc){
	// Clear all 2 possibilities.
	this._mConsole.cleanFailure('quantity');
	this._mConsole.cleanFailure('bar_code');
	
	this._mDetails.getLastPage();
	
	// Clear elements.
	this._mQuantity.value = '1';
	this._mBarCode.value = '';
	this._mProductName.value = '';
}

/**
 * Clean the input elements from previous failures and display the actual failure.
 * @param {DocumentElement} xmlDoc
 * @param {String} strMsg
 */
AddProductObjectCommand.prototype.displayFailure = function(xmlDoc, strMsg){
	var elementId = xmlDoc.getElementsByTagName('element_id')[0].firstChild.data;
	
	// Must clean the 2 possibilities in case a failure has been already been display.
	this._mConsole.cleanFailure('quantity');
	this._mConsole.cleanFailure('bar_code');
	this._mConsole.displayFailure(strMsg, elementId);
	StateMachine.setFocus(elementId);
}