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
 * @param {String} sCmd
 */
function DeleteProductObjectCommand(oSession, oConsole, oRequest, sKey, oDetails, sCmd){
	// Call the parent constructor.
	DeleteDetailCommand.call(this, oSession, oConsole, oRequest, sKey, oDetails, sCmd);
}

/**
* Inherit the Sync command class methods.
*/
DeleteProductObjectCommand.prototype = new DeleteDetailCommand();

/**
 * Updates the page, sets the focus on the details element on the last selected row position.
 * @param {DocumentElement} xmlDoc
 */
DeleteProductObjectCommand.prototype.displaySuccess = function(xmlDoc){
	this._mDetails.updatePage();
	this._mDetails.setFocus();
	this._mDetails.moveTo(this._mRowPos, this._mPage);
}