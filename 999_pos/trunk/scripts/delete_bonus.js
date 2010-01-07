/**
 * @fileOverview Library with the DeleteBonusCommand class.
 * @author Roberto Oliveros
 */

/**
 * @class Deletes an object on the server and refresh the items list.
 * @extends AlterObjectCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 * @param {String} sKey
 * @param {String} sCmd
 * @param {ObjectDetails} oDetails
 */
function DeleteBonusCommand(oSession, oConsole, oRequest, sKey, sCmd, oDetails){
	// Call the parent constructor.
	AlterObjectCommand.call(this, oSession, oConsole, oRequest, sKey, sCmd);
	
	/**
	 * Holds the object that manage the list of bonus.
	 * @type ObjectDetails
	 */
	this._mDetails = oDetails;
}

/**
 * Inherit the Sync command class methods.
 */
DeleteBonusCommand.prototype = new AlterObjectCommand();

/**
 * Updates the bonus list.
 * @param {DocumentElement} xmlDoc
 */
DeleteBonusCommand.prototype.displaySuccess = function(xmlDoc){
	this._mDetails.update();
}