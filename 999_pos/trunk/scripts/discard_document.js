/**
 * @fileOverview Library with the DiscardDocumentCommand class.
 * @author Roberto Oliveros
 */

/**
 * @class Discards an unsaved document on the server.
 * @extends SyncCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 * @param {String} sKey
 */
function DiscardDocumentCommand(oSession, oConsole, oRequest, sKey){
	// Call the parent constructor.
	SyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the key of the session object.
	 * @type String
	 */
	this._mKey = sKey;
	
	/**
	 * Holds the command name on the server.
	 * @type String
	 */
	this._mCmd = 'discard_document';
	
	/**
	 * Holds the link to redirect to in case of success.
	 * @type String
	 */
	this._mLinkSuccess = '';
}

/**
 * Inherit the Sync command class methods.
 */
DiscardDocumentCommand.prototype = new SyncCommand();
 
/**
  * Executes the command.
  * @param {String} sLinkSuccess
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
 * Redirects the html document to the success link.
 * @param DocumentElement xmlDoc
 */
DiscardDocumentCommand.prototype.displaySuccess = function(xmlDoc){
	this._mSession.loadHref(this._mLinkSuccess);
}