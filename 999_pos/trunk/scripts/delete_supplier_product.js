/**
 * Library with the delete supplier product command class.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 * @param Session oSession
 * @param Console oConsole
 * @param Request oRequest
 * @param string sKey
 * @param ProductSuppliers oProductSuppliers
 */
function DeleteSupplierProductCommand(oSession, oConsole, oRequest, sKey, oProductSuppliers){
	// Call the parent constructor.
	DeleteDetailCommand.call(this, oSession, oConsole, oRequest, sKey, oProductSuppliers);
}

/**
* Inherit the Sync command class methods.
*/
DeleteSupplierProductCommand.prototype = new DeleteDetailCommand();

/**
* Method for displaying success.
* @param DocumentElement xmlDoc
*/
DeleteSupplierProductCommand.prototype.displaySuccess = function(xmlDoc){
	this._mDetails.update();
	this._mDetails.setFocus();
	this._mDetails.moveTo(this._mRowPos);
}