/**
 * @fileOverview Library with the MakeDefaultCorrelativeCommand class.
 * @author Roberto Oliveros
 */

/**
 * @class Makes a correlative the default correlative on the server.
 * @extends SyncCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 * @param {String} sKey
 */
function MakeDefaultCorrelativeCommand(oSession, oConsole, oRequest, sKey){
	// Call the parent constructor.
	SyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the key of the session object.
	 * @type String
	 */
	this._mKey = sKey;
	
	/**
	 * Holds the name of the command to execute on the server.
	 * @type String
	 */
	this._mCmd = 'make_default_correlative';
	
	/**
	 * Holds a reference to the serial number span element.
	 * @type HtmlElement
	 */
	this._mSerialNumber = null;
	
	/**
	 * Holds a reference to the default input element.
	 * @type HtmlElement
	 */
	this._mDefault = null;
}

/**
* Inherit the Sync command class methods.
*/
MakeDefaultCorrelativeCommand.prototype = new SyncCommand();

/**
 * Sets the serial number span and default input elements.
 * @param {String} sSerialNumber The id of the serial number span element.
 * @param {String} sDefault The id of the default input element.
 */
MakeDefaultCorrelativeCommand.prototype.init = function(sSerialNumber, sDefault){
	this._mSerialNumber = document.getElementById(sSerialNumber);
	this._mDefault = document.getElementById(sDefault);
}
 
/**
 * Executes the command.
 */
MakeDefaultCorrelativeCommand.prototype.execute = function(sCmd){
 		 var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmd);
 		 str = Url.addUrlParam(str, 'key', this._mKey);
 		 this.sendRequest(str);
}

/**
 * Updates the correlative to Default status.
 * @param {DocumentElement} xmlDoc
 */
MakeDefaultCorrelativeCommand.prototype.displaySuccess = function(xmlDoc){
	this._mSerialNumber.innerHTML = this._mSerialNumber.innerHTML + ' (Predeterminado)';
	this._mDefault.disabled = true;
}

/**
 * Displays the failure on the console.
 * @param {DocumentElement} xmlDoc
 * @param {String} strMsg
 */
MakeDefaultCorrelativeCommand.prototype.displayFailure = function(xmlDoc, strMsg){
	// Must clean it in case a failure has been already display for the same element.
	this._mConsole.displayError(strMsg);
}