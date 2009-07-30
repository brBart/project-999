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
 * Executes the command. Receives the name of the command to execute on success.
 * @param string sCmdSuccess
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
* Method for displaying success.
* @param DocumentElement xmlDoc
*/
SaveObjectCommand.prototype.displaySuccess = function(xmlDoc){
	var iId = xmlDoc.getElementsByTagName('id')[0].firstChild.data;
	var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmdSuccess);
	str = Url.addUrlParam(str, 'id', iId);
	// Remove the object from the session on the server.
	str = Url.addUrlParam(str, 'key', this._mKey);
	this._mSession.loadHref(str);
}

/**
* Method for displaying failure.
* @param DocumentElement xmlDoc
* @param string msg
*/
SaveObjectCommand.prototype.displayFailure = function(xmlDoc, strMsg){
	var elementId = xmlDoc.getElementsByTagName('elementid')[0].firstChild.data;
	
	// Must clean it in case a failure has been already display for the same element.
	this._mConsole.reset();
	this._mConsole.displayFailure(strMsg, elementId);
}