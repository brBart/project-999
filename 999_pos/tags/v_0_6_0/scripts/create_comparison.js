/**
 * @fileOverview Library with the CreateComparisonCommand class.
 * @author Roberto Oliveros
 */

/**
 * @class Creates a comparison document on the server.
 * @extends SyncCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 */
function CreateComparisonCommand(oSession, oConsole, oRequest){
	// Call the parent constructor.
	SyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the command name on the server.
	 * @type String
	 */
	this._mCmd = 'create_comparison';
	
	/**
	 * Holds the link to redirect to in case of success.
	 * @type String
	 */
	this._mLinkSuccess = '';
}

/**
 * Inherit the Sync command class methods.
 */
CreateComparisonCommand.prototype = new SyncCommand();
 

/**
 * Sets the input elements from where the values will be read.
 * @param {String} sBarCode The id of the reason input element.
 * @param {String} sQuantity The id of the count_id input element.
 * @param {String} sProductName The id of the general input element.
 */
CreateComparisonCommand.prototype.init = function(sReason, sCountId, sGeneral){
 	this._mReason = document.getElementById(sReason);
 	this._mCountId = document.getElementById(sCountId);
 	this._mGeneral = document.getElementById(sGeneral);
}

/**
 * Executes the command.
 * @param {String} sLinkSuccess The link to redirect to in case of success.
 */
CreateComparisonCommand.prototype.execute = function(sLinkSuccess){
	if(sLinkSuccess == '')
		this._mConsole.displayError('Interno: Argumento sLinkSuccess inv&aacute;lidos.');
	else{
		this._mLinkSuccess = sLinkSuccess;
		var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmd);
		str = Url.addUrlParam(str, 'reason', this._mReason.value);
		str = Url.addUrlParam(str, 'count_id', this._mCountId.value);
		str = Url.addUrlParam(str, 'general', this._mGeneral.checked ? '1' : '0');
		this.sendRequest(str);
	}
}

/**
 * Retrieves the new id parameter from the document and redirects the html document to the success link.
 * @param {DocumentElement} xmlDoc
 */
CreateComparisonCommand.prototype.displaySuccess = function(xmlDoc){
	var iId = xmlDoc.getElementsByTagName('id')[0].firstChild.data;
	str = Url.addUrlParam(this._mLinkSuccess, 'id', iId);
	this._mSession.loadHref(str);
}

/**
 * Displays the failure and sets the focus on the provoking input element.
 * @param {DocumentElement} xmlDoc
 * @param {String} strMsg
 */
CreateComparisonCommand.prototype.displayFailure = function(xmlDoc, strMsg){
	var elementId = xmlDoc.getElementsByTagName('element_id')[0].firstChild.data;
	
	// Must clean it in case a failure has been already display for the same element.
	this._mConsole.reset();
	this._mConsole.displayFailure(strMsg, elementId);
	StateMachine.setFocus(elementId);
}