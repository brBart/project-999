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
function SaveCommand(oSession, oConsole, oRequest, sKey){
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
	this._mCmd = 'save_object';
	
	/**
	 * Holds the name of the command to execute on the server in case of success.
	 * @var string
	 */
	this._mLinkSuccess = '';
}

/**
* Inherit the Sync command class methods.
*/
SaveCommand.prototype = new SyncCommand();

/**
 * Executes the command. Receives the name of the command to execute on success.
 * @param string sLinkSuccess
 */
SaveCommand.prototype.execute = function(sLinkSuccess){
	if(sLinkSuccess == '')
		this._mConsole.displayError('Interno: Argumento sLinkSuccess inv&aacute;lidos.');
	else{
		this._mLinkSuccess = sLinkSuccess;
		var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmd);
		str = Url.addUrlParam(str, 'key', this._mKey);
		this.sendRequest(str);
	}
}

/**
* Method for displaying success.
* @param DocumentElement xmlDoc
*/
SaveCommand.prototype.displaySuccess = function(xmlDoc){
	var iId = xmlDoc.getElementsByTagName('id')[0].firstChild.data;
	str = Url.addUrlParam(this._mLinkSuccess, 'id', iId);
	this._mSession.loadHref(str);
}

/**
* Method for displaying failure.
* @param DocumentElement xmlDoc
* @param string msg
*/
SaveCommand.prototype.displayFailure = function(xmlDoc, strMsg){
	var elementId = xmlDoc.getElementsByTagName('elementid')[0].firstChild.data;
	
	// Must clean it in case a failure has been already display for the same element.
	this._mConsole.reset();
	this._mConsole.displayFailure(strMsg, elementId);
	StateMachine.setFocus(elementId);
}