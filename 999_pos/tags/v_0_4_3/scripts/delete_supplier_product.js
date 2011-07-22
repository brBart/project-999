/**
 * @fileOverview Library with the DeleteSupplierProductCommand class.
 * @author Roberto Oliveros
 */

/**
 * @class Deletes a supplier from a product on the server.
 * @extends DeleteDetailCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 * @param {String} sKey
 * @param {ProductSuppliers} oProductSuppliers
 * @param {String} sCmd
 */
function DeleteSupplierProductCommand(oSession, oConsole, oRequest, sKey, oProductSuppliers, sCmd){
	// Call the parent constructor.
	DeleteDetailCommand.call(this, oSession, oConsole, oRequest, sKey, oProductSuppliers, sCmd);
}

/**
 * Inherit the Sync command class methods.
 */
DeleteSupplierProductCommand.prototype = new DeleteDetailCommand();

/**
 * Updates the suppliers object, sets the focus on the details element on the last selected row position.
 * @param {DocumentElement} xmlDoc
 */
DeleteSupplierProductCommand.prototype.displaySuccess = function(xmlDoc){
	this._mDetails.update();
	this._mDetails.setFocus();
	this._mDetails.moveTo(this._mRowPos);
}