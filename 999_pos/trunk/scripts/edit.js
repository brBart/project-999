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
 * @param StateMachine oMachine
 */
function EditCommand(oSession, oConsole, oRequest, oMachine){
	// Call the parent constructor.
	SyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the form state machine.
	 * @var StateMachine
	 */
	this._mMachine = oMachine;
	
	/**
	 * Holds the id of the element to receive focus in case on success.
	 * @var string
	 */
	this._mElementId = '';
}

/**
* Inherit the Sync command class methods.
*/
EditCommand.prototype = new SyncCommand();

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
	this._mMachine.changeToEditState(this._mElementId);
}