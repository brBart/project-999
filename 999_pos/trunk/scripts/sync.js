/**
 * Library with the Sync command base class.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 * @param Session oSession
 * @param Console oConsole
 * @param Request oRequest
 */
function SyncCommand(oSession, oConsole, oRequest){
	// Call the parent constructor.
	Command.call(this, oSession, oConsole, oRequest);
}

/**
* Inherit the Command class methods.
*/
SyncCommand.prototype = new Command();

/**
 * Send the request to the server.
 * @param string sUrlParams
 */
SyncCommand.prototype.sendRequest = function(sUrlParams){
	sUrlParams = Url.addUrlParam(sUrlParams, 'type', 'xml');
	this._mRequest.open('GET', sUrlParams, false);
	this._mRequest.send(null);
	this.handleRequestStateChange();
}


