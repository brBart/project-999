/**
 * Library with the save object command class.
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
function SaveObjectCommand(oSession, oConsole, oRequest, sKey){
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
	this._mCmd = 'save_object';
	
	/**
	 * Holds the name of the command to execute on the server in case of success.
	 * @var string
	 */
	this._mCmdSuccess = '';
}

/**
* Inherit the Async command class methods.
*/
SaveObjectCommand.prototype = new AsyncCommand();

/**
 * Executes the command.
 */
SaveObjectCommand.prototype.execute = function(sCmdSuccess){
	if(sCmdSuccess == '')
		this._mConsole.displayError('Interno: Argumento sCmdSuccess inv&aacute;lidos.');
	else{
		this._mCmdSuccess = sCmdSuccess;
		var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmd);
		str = Url.addUrlParam(str, 'key', this._mKey);
		this.sendRequest(str);
	}
}

/**
* Function for displaying success.
* @param DocumentElement xmlDoc
*/
SaveObjectCommand.prototype.displaySuccess = function(xmlDoc){
	var iId = xmlDoc.getElementsByTagName('id')[0].firstChild.data;
	var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmdSuccess);
	str = Url.addUrlParam(str, 'id', iId);
	this._mSession.loadHref(str);
}