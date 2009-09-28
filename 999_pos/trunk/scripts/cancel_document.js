/**
 * Library with the cancel document command base class.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 * @param Session oSession
 * @param Console oConsole
 * @param Request oRequest
 * @param string sKey
 */
function CancelDocumentCommand(oSession, oConsole, oRequest, sKey, oStateMachine){
	// Call the parent constructor.
	SyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the key of the session object.
	 * @var string
	 */
	this._mKey = sKey;
	
	/**
	 * Holds the form state machine.
	 * @var StateMachine
	 */
	this._mMachine = oMachine;
	
	/**
	 * Holds a reference to the div form element.
	 * @var object
	 */
	this._mForm = null;
	
	/**
	 * Holds a reference to the user input element.
	 * @var object
	 */
	this._mUser = null;
	
	/**
	 * Holds a reference to the password input element.
	 * @var object
	 */
	this._mPassword = null;
	
	/**
	 * Will store the initial value of the wrapper div height.
	 * @var integer
	 */
	this._mInitHeight = 0;
	
	/**
	 * Will store the initial value of the body width.
	 * @var integer
	 */
	this._mInitWidth = 0;
}

/**
* Inherit the Sync command class methods.
*/
CancelDocumentCommand.prototype = new SyncCommand();

/**
 * Set the input elements from where the values will be read.
 * @param string sForm
 * @param string sUser
 * @param string sPassword
 */
CancelDocumentCommand.prototype.init = function(sForm, sUser, sPassword){
	this._mForm = document.getElementById(sForm);
	this._mUser = document.getElementById(sUser);
	this._mPassword = document.getElementById(sPassword);
	
	// Store the original wrapper div height and body width values.
	this._mInitHeight = document.getElementById('wrapper').scrollHeight;
	this._mInitWidth = document.body.scrollWidth;
}

/**
 * Shows the authentication form.
 */
CancelDocumentCommand.prototype.showForm = function(){
	// Because height changes dynamically.
	if(this._mInitHeight < document.getElementById('wrapper').scrollHeight){
		// The window changed its size.
		this._mForm.style.height = document.getElementById('wrapper').scrollHeight + 'px';
		this._mForm.style.width = document.body.scrollWidth + 'px';
	}
	
	this._mForm.className = 'authenticate_form';
	this._mUser.focus();
}

/**
 * Hides the authentication form.
 */
CancelDocumentCommand.prototype.hideForm = function(){
	this._mConsole.reset();
	this._mUser.value = '';
	this._mPassword.value = '';
	this._mForm.className = 'hidden';
}
 
/**
  * Executes the command. Retreives the username and password.
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
* Method for displaying success.
* @param DocumentElement xmlDoc
*/
CancelDocumentCommand.prototype.displaySuccess = function(xmlDoc){
	this._mMachine.changeToCancelState();
	this._mConsole.reset();
	this._mUser.value = '';
	this._mPassword.value = '';
	this._mForm.className = 'hidden';
}

/**
* Method for displaying failure.
* @param DocumentElement xmlDoc
* @param string msg
*/
CancelDocumentCommand.prototype.displayFailure = function(xmlDoc, strMsg){
	// Must clean it in case a failure has been already display for the same element.
	this._mConsole.displayError(strMsg);
	this._mUser.focus();
}