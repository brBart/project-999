/**
 * Library with the get property command class.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 * @param Session oSession
 * @param Console oConsole
 * @param Request oRequest
 */
function GetPropertyCommand(oSession, oConsole, oRequest, sKey){
	// Call the parent constructor.
	AsyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the key of the session object.
	 * @var string
	 */
	this._mKey = sKey;
	
	/**
	 * Holds the name of the command to use on the server.
	 */
	this._mCmd = '';
}

/**
* Inherit the AsyncCommand class methods.
*/
GetPropertyCommand.prototype = new AsyncCommand();

/**
 * Sets the name of the command to use on the server.
 * @param string sCmd
 */
GetPropertyCommand.prototype.init = function(sCmd){
	this._mCmd = sCmd;
}

/**
 * Executes the command.
 */
GetPropertyCommand.prototype.execute = function(){
	var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmd);
	str = Url.addUrlParam(str, 'key', this._mKey);
	this.sendRequest(str);
}