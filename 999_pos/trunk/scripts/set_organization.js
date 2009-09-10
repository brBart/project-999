/**
 * Library with the set organization command class.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 * @param Session oSession
 * @param Console oConsole
 * @param Request oRequest
 */
function SetOrganizationCommand(oSession, oConsole, oRequest, sKey){
	// Call the parent constructor.
	AsyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the key of the session object.
	 * @var string
	 */
	this._mKey = sKey;
}

/**
* Inherit the AsyncCommand class methods.
*/
SetOrganizationCommand.prototype = new AsyncCommand();

/**
 * Executes the command.
 * @param string sCmd
 * @param string sValue
 * @param string sElementId
 */
SetOrganizationCommand.prototype.execute = function(sCmd, sOrganizationId, sElementId){
	if(sCmd == '' || sElementId == '')
		this._mConsole.displayError('Interno: Argumentos sCmd y sElementId inv&aacute;lidos.');
	else{
		var str = Url.addUrlParam(Url.getUrl(), 'cmd', sCmd);
		str = Url.addUrlParam(str, 'key', this._mKey);
		str = Url.addUrlParam(str, 'organization_id', sOrganizationId);
		str = Url.addUrlParam(str, 'element_id', sElementId);
		this.sendRequest(str);
	}
}

/**
* Method for displaying success.
* @param DocumentElement xmlDoc
*/
SetOrganizationCommand.prototype.displaySuccess = function(xmlDoc){
	var elementId = xmlDoc.getElementsByTagName('element_id')[0].firstChild.data;
	this._mConsole.cleanFailure(elementId);
}

/**
* Method for displaying failure.
* @param DocumentElement xmlDoc
* @param string msg
*/
SetOrganizationCommand.prototype.displayFailure = function(xmlDoc, strMsg){
	var elementId = xmlDoc.getElementsByTagName('element_id')[0].firstChild.data;
	
	// Must clean it in case a failure has been already display for the same element.
	this._mConsole.cleanFailure(elementId);
	this._mConsole.displayFailure(strMsg, elementId);
}