/**
 * Library with the save command class.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 * @param Session oSession
 * @param Console oConsole
 * @param Request oRequest
 * @param string sKey
 */
function DeleteCommand(oSession, oConsole, oRequest, sKey){
	// Call the parent constructor.
	AsyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the key of the session object.
	 * @var string
	 */
	this._mKey = sKey;
	
	/**
	 * Holds the name of the command to execute on the server in case of success.
	 * @var string
	 */
	this._mCmdSuccess = '';
}

/**
* Inherit the Async command class methods.
*/
DeleteCommand.prototype = new AsyncCommand();

/**
 * Executes the command. Receives the name of the command to execute on the server and on success.
 * @param string sCmd
 * @param string sCmdSuccess
 */
DeleteCommand.prototype.execute = function(sCmd, sCmdSuccess){
	if(sCmd == '' || sCmdSuccess == '')
		this._mConsole.displayError('Interno: Argumentos sCmd y sCmdSuccess inv&aacute;lidos.');
	else{
		this._mCmdSuccess = sCmdSuccess;
		var str = Url.addUrlParam(Url.getUrl(), 'cmd', sCmd);
		str = Url.addUrlParam(str, 'key', this._mKey);
		this.sendRequest(str);
	}
}

/**
* Method for displaying success.
* @param DocumentElement xmlDoc
*/
DeleteCommand.prototype.displaySuccess = function(xmlDoc){
	var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmdSuccess);
	// Remove the object from the session on the server.
	str = Url.addUrlParam(str, 'key', this._mKey);
	this._mSession.loadHref(str);
}