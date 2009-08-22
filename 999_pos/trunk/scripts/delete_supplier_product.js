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
* Method for displaying success.
* @param DocumentElement xmlDoc
*/
AddSupplierProductCommand.prototype.displaySuccess = function(xmlDoc){
	this._mDetails.update();
	this._mDetails.moveTo(this._mRowPos);
}