/**
 * Library with the discard document command class.
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
function DiscardDocumentCommand(oSession, oConsole, oRequest, sKey){
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
	this._mCmd = 'discard_document';
	
	/**
	 * Holds the link to redirect the page in case of success.
	 * @var string
	 */
	this._mLinkSuccess = '';
}

/**
* Inherit the Sync command class methods.
*/
DiscardDocumentCommand.prototype = new SyncCommand();
 
/**
  * Executes the command.
  * @param string sLinkSuccess
  */
DiscardDocumentCommand.prototype.execute = function(sLinkSuccess){
 	 if(sLinkSuccess == '')
 		 this._mConsole.displayError('Interno: Argumento sLinkSuccess inv&aacute;lido.');
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
DiscardDocumentCommand.prototype.displaySuccess = function(xmlDoc){
	this._mSession.loadHref(this._mLinkSuccess);
}