/**
 * Library with the save command base class.
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
 */
function SaveCommand(oSession, oConsole, oRequest, sKey, sCmd){
	// Call the parent constructor.
	AsyncCommand.call(this, oSession, oConsole, oRequest);
	
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
}

/**
* Inherit the Async command class methods.
*/
SaveCommand.prototype = new AsyncCommand();