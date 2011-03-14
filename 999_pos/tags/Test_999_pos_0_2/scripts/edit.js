/**
 * @fileOverview Library with the EditCommand class.
 * @author Roberto Oliveros
 */

/**
 * @class Checks if the actual user has the rights to edit the session object.
 * @extends SyncCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 * @param {StateMachine} oMachine
 */
function EditCommand(oSession, oConsole, oRequest, oMachine){
	// Call the parent constructor.
	SyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the form's state machine.
	 * @type StateMachine
	 */
	this._mMachine = oMachine;
	
	/**
	 * Holds the id of the input element to receive focus in case of success.
	 * @type String
	 */
	this._mElementId = '';
}

/**
 * Inherit the Sync command class methods.
 */
EditCommand.prototype = new SyncCommand();

/**
 * Executes the command.
 * @param {String} sCmd The name of the command on the server.
 * @param {String} sElementId The id of the input element to receive focus.
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
 * Change the form's state to edit state and set focus on the input element.
 * @param {DocumentElement} xmlDoc
 */
EditCommand.prototype.displaySuccess = function(xmlDoc){
	this._mMachine.changeToEditState(this._mElementId);
}