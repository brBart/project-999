/**
 * @fileOverview Library with the SaveUserAccountCommand class.
 * @author Roberto Oliveros
 */

/**
 * @class Saves a new or altered user account object on the server.
 * @extends SaveCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 * @param {String} sKey
 */
function SaveUserAccountCommand(oSession, oConsole, oRequest, sKey){
	// Call the parent constructor.
	SaveCommand.call(this, oSession, oConsole, oRequest, sKey);
	
	/**
	 * Holds the reference to the password input element.
	 * @var HtmlElement
	 */
	this._mPassword = null;
	
	/**
	 * Holds the reference to the confirm input element.
	 * @var HtmlElement
	 */
	this._mConfirm = null;
}

/**
 * Inherit the Save command class methods.
 */
SaveUserAccountCommand.prototype = new SaveCommand();
 
/**
 * Sets the password and confirm inputs elements.
 * @param {String} sPassword The id of the password input element.
 * @param {String} sConfirm The id of the confirm input element.
 */
SaveUserAccountCommand.prototype.init = function(sPassword, sConfirm){
	this._mPassword = document.getElementById(sPassword);
	this._mConfirm = document.getElementById(sConfirm);
}

/**
 * Executes the command.
 * @param {String} sLinkSuccess The link to redirect to in case of success.
 */
SaveUserAccountCommand.prototype.execute = function(sLinkSuccess){
	if(this._mPassword.value != this._mConfirm.value)
		this._mConsole.displayFailure('Confirmaci&oacute;n de la contrase&ntilde;a no coincide.', 'confirm_password');
	else
		// Call parent method.
		SaveCommand.prototype.execute.call(this, sLinkSuccess);
}