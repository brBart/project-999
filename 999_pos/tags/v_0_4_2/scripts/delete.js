/**
 * @fileOverview Library with the DeleteCommand class.
 * @author Roberto Oliveros
 */

/**
 * @class Deletes an object (from the database) on the server.
 * @extends SyncCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 * @param {String} sKey
 */
function DeleteCommand(oSession, oConsole, oRequest, sKey){
	// Call the parent constructor.
	SyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the key of the session object.
	 * @type String
	 */
	this._mKey = sKey;
	
	/**
	 * Holds the link to redirect to in case of success.
	 * @type String
	 */
	this._mLinkSuccess = '';
}

/**
 * Inherit the Sync command class methods.
 */
DeleteCommand.prototype = new SyncCommand();

/**
 * Executes the command.
 * @param {String} sCmd The name of the command to execute on the server.
 * @param {String} sCmdSuccess The link to redirect to in case of success.
 */
DeleteCommand.prototype.execute = function(sCmd, sLinkSuccess){
	if(sCmd == '' || sLinkSuccess == '')
		this._mConsole.displayError('Interno: Argumentos sCmd y sCmdSuccess inv&aacute;lidos.');
	else{
		this._mLinkSuccess = sLinkSuccess;
		var str = Url.addUrlParam(Url.getUrl(), 'cmd', sCmd);
		str = Url.addUrlParam(str, 'key', this._mKey);
		this.sendRequest(str);
	}
}

/**
 * Redirects the html document to the success link.
 * @param {DocumentElement} xmlDoc
 */
DeleteCommand.prototype.displaySuccess = function(xmlDoc){
	this._mSession.loadHref(this._mLinkSuccess);
}