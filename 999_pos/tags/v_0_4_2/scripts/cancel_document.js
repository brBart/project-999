/**
 * @fileOverview Library with the CancelDocumentCommand class.
 * @author Roberto Oliveros
 */

/**
 * @class Cancels a document on the server.
 * @extends SyncCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 * @param {String} sKey
 * @param {StateMachine} oStateMachine
 * @param {ModalForm} oForm
 */
function CancelDocumentCommand(oSession, oConsole, oRequest, sKey, oStateMachine, oForm){
	// Call the parent constructor.
	SyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the key of the session object.
	 * @type String
	 */
	this._mKey = sKey;
	
	/**
	 * Holds the form's state machine.
	 * @type StateMachine
	 */
	this._mMachine = oMachine;
	
	/**
	 * Holds a reference to the modal form object.
	 * @type ModalForm
	 */
	this._mForm = oForm;
	
	/**
	 * Holds a reference to the user input element.
	 * @type HtmlElement
	 */
	this._mUser = null;
	
	/**
	 * Holds a reference to the password input element.
	 * @type HtmlElement
	 */
	this._mPassword = null;
}

/**
* Inherit the Sync command class methods.
*/
CancelDocumentCommand.prototype = new SyncCommand();

/**
 * Sets the input elements from where the values will be read.
 * @param {String} sUser The id of the user input element.
 * @param {String} sPassword The id of the password input element.
 */
CancelDocumentCommand.prototype.init = function(sUser, sPassword){
	this._mUser = document.getElementById(sUser);
	this._mPassword = document.getElementById(sPassword);
}

/**
 * Shows the authentication form div element.
 */
CancelDocumentCommand.prototype.showForm = function(){
	this._mForm.show();
	this._mUser.focus();
}

/**
 * Hides the authentication form div element.
 */
CancelDocumentCommand.prototype.hideForm = function(){
	this._mConsole.reset();
	this._mUser.value = '';
	this._mPassword.value = '';
	this._mForm.hide();
}
 
/**
 * Executes the command. Sends the username and password.
 * @param {String} sCmd The name of the command to execute on the server.
 */
CancelDocumentCommand.prototype.execute = function(sCmd){
 	 if(sCmd == '')
 			this._mConsole.displayError('Interno: Argumento sCmd inv&aacute;lido.');
 	 else{
 		 var str = Url.addUrlParam(Url.getUrl(), 'cmd', sCmd);
 		 str = Url.addUrlParam(str, 'key', this._mKey);
 		 str = Url.addUrlParam(str, 'username', this._mUser.value);
 		 str = Url.addUrlParam(str, 'password', this._mPassword.value);
 		 this.sendRequest(str);
 	 }
}

/**
 * Change the document form state to cancel state, clean previous failures and the input elements, and hides the form.
 * @param {DocumentElement} xmlDoc
 */
CancelDocumentCommand.prototype.displaySuccess = function(xmlDoc){
	this._mMachine.changeToCancelState();
	this._mConsole.reset();
	this._mUser.value = '';
	this._mPassword.value = '';
	this._mForm.hide();
}

/**
 * Displays the failure on the console and sets the focus on the user input element.
 * @param {DocumentElement} xmlDoc
 * @param {String} strMsg
 */
CancelDocumentCommand.prototype.displayFailure = function(xmlDoc, strMsg){
	// Must clean it in case a failure has been already display for the same element.
	this._mConsole.displayError(strMsg);
	
	this._mPassword.value = '';
	
	TextRange.selectRange(this._mUser, 0, this._mUser.value.length);
	this._mUser.focus();
}