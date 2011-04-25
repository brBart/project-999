/**
 * @fileOverview Library with the SaveUniqueObjectCommand class.
 * @author Roberto Oliveros
 */

/**
 * @class Saves the altered object on the server.
 * @extends SaveCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 * @param {String} sKey
 */
function SaveUniqueObjectCommand(oSession, oConsole, oRequest, sKey){
	// Call the parent constructor.
	SaveCommand.call(this, oSession, oConsole, oRequest, sKey);
	
	/**
	 * Holds the command name on the server.
	 * @type String
	 */
	this._mCmd = 'save_unique_object';
}

/**
 * Inherit the Save command class methods.
 */
SaveUniqueObjectCommand.prototype = new SaveCommand();

/**
 * Redirects the html document to the success link.
 * @param {DocumentElement} xmlDoc
 */
SaveUniqueObjectCommand.prototype.displaySuccess = function(xmlDoc){
	this._mSession.loadHref(this._mLinkSuccess);
}