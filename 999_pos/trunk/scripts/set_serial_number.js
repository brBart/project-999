/**
 * @fileOverview Library with the SetSerialNumberCommand class.
 * @author Roberto Oliveros
 */

/**
 * @class Sets the serial number to a correlative object on the server.
 * @extends AsyncCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 * @param {String} sKey
 */
function SetSerialNumberCommand(oSession, oConsole, oRequest, sKey){
	// Call the parent constructor.
	AsyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the key of the session object.
	 * @type String
	 */
	this._mKey = sKey;
	
	/**
	 * Holds the name of the command to execute on the server.
	 * @type String
	 */
	this._mCmd = 'set_serial_number_correlative';
	
	/**
	 * Holds the initial number span element.
	 * @type HtmlElement
	 */
	this._mInitialNumber = null;
}

/**
* Inherit the AsyncCommand class methods.
*/
SetSerialNumberCommand.prototype = new AsyncCommand();

/**
 * Sets the initial number span element to display.
 * @param {String} sInitialNumber The id of the contact input element.
 */
SetSerialNumberCommand.prototype.init = function(sInitialNumber){
	this._mInitialNumber = document.getElementById(sInitialNumber);
}

/**
 * Executes the command.
 * @param {String} sCmd The name of the command to execute on the server.
 * @param {String} sSerialNumber
 * @param {String} sElementId The id of the serial number input element.
 */
SetSerialNumberCommand.prototype.execute = function(sSerialNumber, sElementId){
	if(sElementId == '')
		this._mConsole.displayError('Interno: Argumento sElementId inv&aacute;lido.');
	else{
		var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmd);
		str = Url.addUrlParam(str, 'key', this._mKey);
		str = Url.addUrlParam(str, 'serial_number', sSerialNumber);
		str = Url.addUrlParam(str, 'element_id', sElementId);
		this.sendRequest(str);
	}
}

/**
 * Cleans any previous failures and the displays the initial number for the correlative.
 * @param {DocumentElement} xmlDoc
 */
SetSerialNumberCommand.prototype.displaySuccess = function(xmlDoc){
	var elementId = xmlDoc.getElementsByTagName('element_id')[0].firstChild.data;
	var sInitialNumber = xmlDoc.getElementsByTagName('initial_number')[0].firstChild.data;
	this._mConsole.cleanFailure(elementId);
	this._mInitialNumber.innerHTML = sInitialNumber;
}

/**
 * Displays the message on the console and points out the input element.
 * @param {DocumentElement} xmlDoc
 * @param {String} strMsg
 */
SetSerialNumberCommand.prototype.displayFailure = function(xmlDoc, strMsg){
	var elementId = xmlDoc.getElementsByTagName('element_id')[0].firstChild.data;
	
	// Must clean it in case a failure has been already display for the same element.
	this._mConsole.cleanFailure(elementId);
	this._mConsole.displayFailure(strMsg, elementId);
}