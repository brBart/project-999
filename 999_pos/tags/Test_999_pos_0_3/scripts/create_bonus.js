/**
 * @fileOverview Library with the CreateBonusCommand class.
 * @author Roberto Oliveros
 */

/**
 * @class Creates a bonus on the server.
 * @extends SyncCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHtttpRequest} oRequest
 * @param {String} sKey
 * @param {ObjectDetails} oObjectDetails
 */
function CreateBonusCommand(oSession, oConsole, oRequest, sKey, oObjectDetails){
	// Call the parent constructor.
	SyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the key of the session object.
	 * @type String
	 */
	this._mKey = sKey;
	
	/**
	 * Holds a reference to the object in charge of the list of bonus.
	 * @type ObjectDetails
	 */
	this._mObjectDetails = oObjectDetails;
	
	/**
	 * Holds the command name on the server.
	 * @type String
	 */
	this._mCmd = 'create_bonus';
	
	/**
	 * Holds a reference to the quantity input element.
	 * @type HtmlElement
	 */
	this._mQuantity = null;
	
	/**
	 * Holds a reference to the percentage input element.
	 * @type HtmlElement
	 */
	this._mPercentage = null;
	
	/**
	 * Holds a reference to the expiration date input element.
	 * @type HtmlElement
	 */
	this._mExpirationDate = null;
}

/**
 * Inherit the Sync command class methods.
 */
CreateBonusCommand.prototype = new SyncCommand();

/**
 * Sets the input elements from where the values will be read.
 * @param {String} sQuantity The id of the quantity input element.
 * @param {String} sPercentage The id of the percentage input element.
 * @param {String} sExpirationDate The id of the expiration date input element.
 */
CreateBonusCommand.prototype.init = function(sQuantity, sPercentage, sExpirationDate){
	this._mQuantity = document.getElementById(sQuantity);
	this._mPercentage = document.getElementById(sPercentage);
	this._mExpirationDate = document.getElementById(sExpirationDate);
}

/**
 * Executes the command. Sends the quantity, percentage and the expiration date.
 */
CreateBonusCommand.prototype.execute = function(){
	var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmd);
	str = Url.addUrlParam(str, 'key', this._mKey);
	str = Url.addUrlParam(str, 'quantity', this._mQuantity.value);
	str = Url.addUrlParam(str, 'percentage', this._mPercentage.value);
	str = Url.addUrlParam(str, 'expiration_date', this._mExpirationDate.value);
	this.sendRequest(str);
}

/**
 * Clean the input elements and updates the details object.
 * @param {DocumentElement} xmlDoc
 */
CreateBonusCommand.prototype.displaySuccess = function(xmlDoc){
	// Clear all possibilities.
	this._mConsole.cleanFailure('quantity');
	this._mConsole.cleanFailure('percentage');
	this._mConsole.cleanFailure('expiration_date');
	this._mConsole.cleanFailure('bonus');
	
	this._mObjectDetails.update();
	
	// Clear elements.
	this._mQuantity.value = '';
	this._mPercentage.value = '';
	this._mExpirationDate.value = '';
	this._mQuantity.focus();
}

/**
 * Clean the input elements from previous failures and display the actual failure.
 * @param {DocumentElement} xmlDoc
 * @param {String} strMsg
 */
CreateBonusCommand.prototype.displayFailure = function(xmlDoc, strMsg){
	var elementId = xmlDoc.getElementsByTagName('element_id')[0].firstChild.data;
	
	// Must clean all possibilities in case a failure has been already been display.
	this._mConsole.cleanFailure('quantity');
	this._mConsole.cleanFailure('percentage');
	this._mConsole.cleanFailure('expiration_date');
	this._mConsole.cleanFailure('bonus');
	this._mConsole.displayFailure(strMsg, elementId);
}