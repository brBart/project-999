/**
 * @fileOverview Library with the DeleteReserveCommand class.
 * @author Roberto Oliveros
 */

/**
 * @class Deletes a reserve on the server and refresh the items list.
 * @extends AlterObjectCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 * @param {String} sKey
 * @param {String} sCmd
 * @param {ObjectDetails} oDetails
 */
function DeleteReserveCommand(oSession, oConsole, oRequest, sKey, sCmd, oDetails, oProductBalance){
	// Call the parent constructor.
	AlterObjectCommand.call(this, oSession, oConsole, oRequest, sKey, sCmd);
	
	/**
	 * Holds the object that manage the list of bonus.
	 * @type ObjectDetails
	 */
	this._mDetails = oDetails;
	
	/**
	 * Holds the object in charge of updating the product balance.
	 * @type GetProductBalanceCommand
	 */
	this._mProductBalance = oProductBalance
}

/**
 * Inherit the Sync command class methods.
 */
DeleteReserveCommand.prototype = new AlterObjectCommand();

/**
 * Updates the bonus list.
 * @param {DocumentElement} xmlDoc
 */
DeleteReserveCommand.prototype.displaySuccess = function(xmlDoc){
	this._mDetails.update();
	this._mProductBalance.execute();
}