/**
 * @fileOverview Library with the AlterObjectCommand class.
 * @author Roberto Oliveros
 */

/**
 * @class Alters an object (in the database) on the server.
 * @extends SyncCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 * @param {String} sKey
 * @param {String} sCmd
 */
function AlterObjectCommand(oSession, oConsole, oRequest, sKey, sCmd){
	// Call the parent constructor.
	SyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the key of the session object.
	 * @type String
	 */
	this._mKey = sKey;
	
	/**
	 * Holds the name of the commmand on the server.
	 * @type String
	 */
	this._mCmd = sCmd;
}

/**
 * Inherit the Sync command class methods.
 */
AlterObjectCommand.prototype = new SyncCommand();

/**
 * Executes the command.
 * @param {String} sId The id of the object on the server.
 */
AlterObjectCommand.prototype.execute = function(sId){
	if(sId == '')
		this._mConsole.displayError('Interno: Argumentos sId inv&aacute;lido.');
	else{
		var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmd);
		str = Url.addUrlParam(str, 'id', sId);
		this.sendRequest(str);
	}
}