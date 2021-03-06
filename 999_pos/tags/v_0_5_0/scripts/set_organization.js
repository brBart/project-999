/**
 * @fileOverview Library with the SetOrganizationCommand class.
 * @author Roberto Oliveros
 */

/**
 * @class Sets a organization to an object on the server.
 * @extends AsyncCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 * @param {String} sKey
 * @param {String} sCmd
 */
function SetOrganizationCommand(oSession, oConsole, oRequest, sKey, sCmd){
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
	this._mCmd = sCmd;
	
	/**
	 * Holds the contact input element.
	 * @type HtmlElement
	 */
	this._mContact = null;
}

/**
* Inherit the AsyncCommand class methods.
*/
SetOrganizationCommand.prototype = new AsyncCommand();

/**
 * Sets the contact input element to display the contact name.
 * @param {String} sContact The id of the contact input element.
 */
SetOrganizationCommand.prototype.init = function(sContact){
	this._mContact = document.getElementById(sContact);
}

/**
 * Executes the command.
 * @param {String} sCmd The name of the command to execute on the server.
 * @param {String} sOrganizationId
 * @param {String} sElementId The id of the organization input element.
 */
SetOrganizationCommand.prototype.execute = function(sOrganizationId, sElementId){
	if(sElementId == '')
		this._mConsole.displayError('Interno: Argumento sElementId inv&aacute;lido.');
	else{
		var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmd);
		str = Url.addUrlParam(str, 'key', this._mKey);
		str = Url.addUrlParam(str, 'organization_id', sOrganizationId);
		str = Url.addUrlParam(str, 'element_id', sElementId);
		this.sendRequest(str);
	}
}

/**
 * Cleans any previous failures and the displays the name of the contact.
 * @param {DocumentElement} xmlDoc
 */
SetOrganizationCommand.prototype.displaySuccess = function(xmlDoc){
	var elementId = xmlDoc.getElementsByTagName('element_id')[0].firstChild.data;
	var sContact = xmlDoc.getElementsByTagName('contact')[0].firstChild.data;
	this._mConsole.cleanFailure(elementId);
	this._mContact.value = sContact;
}

/**
 * Displays the message on the console and points out the input element.
 * @param {DocumentElement} xmlDoc
 * @param {String} strMsg
 */
SetOrganizationCommand.prototype.displayFailure = function(xmlDoc, strMsg){
	var elementId = xmlDoc.getElementsByTagName('element_id')[0].firstChild.data;
	
	// Must clean it in case a failure has been already display for the same element.
	this._mConsole.cleanFailure(elementId);
	this._mConsole.displayFailure(strMsg, elementId);
}