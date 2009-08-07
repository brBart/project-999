/**
 * Library with the details base class for all table elements.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 * @param Session oSession
 * @param Console oConsole
 * @param Request oRequest
 * @param string sKey
 * @param string sCmd
 * @param string sTableId
 */
function Details(oSession, oConsole, oRequest, sKey, sCmd, sTableId){
	// Call the parent constructor.
	SyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the key of the session object.
	 * @var string
	 */
	this._mKey = sKey;
	
	/**
	 * Holds the command name on the server.
	 * @var string
	 */
	this._mCmd = sCmd;
	
	/**
	 * Holds the id of the table.
	 * @var string
	 */
	this._mTableId = sTableId;
	
	/**
	 * Holds the XSLT document.
	 * @var object
	 */
	this._mStylesheetDoc = null;
}

/**
* Inherit the Sync command class methods.
*/
Details.prototype = new SyncCommand();