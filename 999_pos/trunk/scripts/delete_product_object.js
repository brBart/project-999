/**
 * @fileOverview Library with the DeleteProductObjectCommand class.
 * @author Roberto Oliveros
 */

/**
 * @class Deletes a product from an object on the server.
 * @extends DeleteDetailCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 * @param {String} sKey
 * @param {DocumentPage} oDetails
 */
function DeleteProductObjectCommand(oSession, oConsole, oRequest, sKey, oDetails){
	// Call the parent constructor.
	DeleteDetailCommand.call(this, oSession, oConsole, oRequest, sKey, oDetails);
}

/**
* Inherit the Sync command class methods.
*/
DeleteProductObjectCommand.prototype = new DeleteDetailCommand();

/**
 * Method for displaying success.
 * @param {DocumentElement} xmlDoc
 */
DeleteProductObjectCommand.prototype.displaySuccess = function(xmlDoc){
	this._mDetails.updatePage();
	this._mDetails.setFocus();
	this._mDetails.moveTo(this._mRowPos, this._mPage);
}