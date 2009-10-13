/**
 * @fileOverview Library with the DeleteDetailCommand base class.
 * @author Roberto Oliveros
 */

/**
 * @class Deletes a detail from an object on the server.
 * @extends SyncCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 * @param {String} sKey
 * @param {Details} oDetails
 * @param {String} sCmd
 */
function DeleteDetailCommand(oSession, oConsole, oRequest, sKey, oDetails, sCmd){
	// Call the parent constructor.
	SyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the key of the session object.
	 * @type String
	 */
	this._mKey = sKey;
	
	/**
	 * Holds a reference to the details object.
	 * @type ProductSuppliers
	 */
	this._mDetails = oDetails;
	
	/**
	 * Holds the command name on the server.
	 * @type String
	 */
	this._mCmd = sCmd;
	
	/**
	 * Holds the row position before the deletion.
	 * @type Integer
	 */
	this._mRowPos = 0;
	
	/**
	 * Holds the page number before the deletion.
	 * @type Integer
	 */
	this._mPage = 0;
}

/**
* Inherit the Sync command class methods.
*/
DeleteDetailCommand.prototype = new SyncCommand();

/**
 * Executes the command.
 */
DeleteDetailCommand.prototype.execute = function(){
	sDetailId = this._mDetails.getDetailId();
	if(sDetailId != ''){
		this._mRowPos = this._mDetails.getPosition();
		this._mPage = this._mDetails.getPageNumber();
		var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmd);
		str = Url.addUrlParam(str, 'key', this._mKey);
		str = Url.addUrlParam(str, 'detail_id', sDetailId);
		this.sendRequest(str);
	}
}