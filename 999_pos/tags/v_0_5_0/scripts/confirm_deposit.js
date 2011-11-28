/**
 * @fileOverview Library with the ConfirmDepositCommand class.
 * @author Roberto Oliveros
 */

/**
 * @class Confirms a deposit on the server.
 * @extends AlterObjectCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 * @param {String} sKey
 * @param {String} sCmd
 * @param {String} sPage Page to use when redirection occurs on succes.
 */
function ConfirmDepositCommand(oSession, oConsole, oRequest, sKey, sCmd, sPage){
	// Call the parent constructor.
	AlterObjectCommand.call(this, oSession, oConsole, oRequest, sKey, sCmd);
	
	/**
	 * Holds the page number.
	 * @type String
	 */
	this._mPage = sPage;
}

/**
 * Inherit the Sync command class methods.
 */
ConfirmDepositCommand.prototype = new AlterObjectCommand();

/**
 * Redirects or refresh the page.
 * @param {DocumentElement} xmlDoc
 */
ConfirmDepositCommand.prototype.displaySuccess = function(xmlDoc){
	this._mSession.loadHref('index.php?cmd=show_pending_deposit_list&page='
			+ this._mPage);
}