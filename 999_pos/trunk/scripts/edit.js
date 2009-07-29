/**
 * Library with the edit command class.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 * @param Session oSession
 * @param Console oConsole
 * @param Request oRequest
 */
function EditCommand(oSession, oConsole, oRequest){
	// Call the parent constructor.
	AsyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the id of the element to receive focus in case on success.
	 */
	this._mElementId = '';
}

/**
* Inherit the Async command class methods.
*/
EditCommand.prototype = new AsyncCommand();

/**
 * Executes the command. Receives the name of the command to execute on the server.
 * @param string sCmd
 * @param string sElementId
 */
EditCommand.prototype.execute = function(sCmd, sElementId){
	if(sCmd == '' || sElementId == '')
		this._mConsole.displayError('Interno: Argumentos sCmd y sElementId inv&aacute;lidos.');
	else{
		this._mElementId = sElementId;
		var str = Url.addUrlParam(Url.getUrl(), 'cmd', sCmd);
		this.sendRequest(str);
	}
}

/**
* Method for displaying success.
* @param DocumentElement xmlDoc
*/
EditCommand.prototype.displaySuccess = function(xmlDoc){
	StateMachine.changeToEditState(this._mElementId);
}