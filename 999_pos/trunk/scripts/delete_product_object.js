/**
 * Library with the delete product object command class.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 * @param Session oSession
 * @param Console oConsole
 * @param Request oRequest
 * @param string sKey
 * @param DocumentPage oDetails
 * @param GetTotalCommand oTotal
 */
function DeleteProductObjectCommand(oSession, oConsole, oRequest, sKey, oDetails, oTotal){
	// Call the parent constructor.
	DeleteDetailCommand.call(this, oSession, oConsole, oRequest, sKey, oDetails);
	
	/**
	 * Holds a reference to the object which displays the total amount.
	 * @var ObjectPage
	 */
	this._mTotal = oTotal;
}

/**
* Inherit the Sync command class methods.
*/
DeleteProductObjectCommand.prototype = new DeleteDetailCommand();

/**
* Method for displaying success.
* @param DocumentElement xmlDoc
*/
DeleteProductObjectCommand.prototype.displaySuccess = function(xmlDoc){
	this._mDetails.updatePage();
	this._mDetails.setFocus();
	this._mDetails.moveTo(this._mRowPos, this._mPage);
	this._mTotal.execute();
}