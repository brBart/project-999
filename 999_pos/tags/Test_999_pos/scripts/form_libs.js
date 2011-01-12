/**
 * @fileOverview Library with the basic classes for controlling a form. AsyncCommand, SyncCommand, StateMachine, RemoveSessionObjectCommand.
 * @author Roberto Oliveros
 */

/**
 * @class Represents a command on the server. Communicates asyncronously.
 * @extends Command
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 */
function AsyncCommand(oSession, oConsole, oRequest){
	// Call the parent constructor.
	Command.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the settimeout id.
	 * @type Integer
	 */
	this._mTimeoutId = 0;
}

/**
* Inherit the Command class methods.
*/
AsyncCommand.prototype = new Command();

/**
 * Send the request to the server.
 * @param {String} sUrlParams The query string with the params to send.
 */
AsyncCommand.prototype.sendRequest = function(sUrlParams){
	// Clear any timeout that was already waiting.
	if(this._mTimeoutId != 0){
		clearTimeout(this._mTimeoutId);
		this._mTimeoutId = 0;
	}
	
	if(this._mRequest.readyState == 4 || this._mRequest.readyState == 0){
		sUrlParams = Url.addUrlParam(sUrlParams, 'type', 'xml');
		this._mRequest.open('GET', sUrlParams, true);
		
		// Necessary for lexical closure, because of the onreadystatchange call.
		var oTemp = this
		this._mRequest.onreadystatechange = function(){
			oTemp.handleRequestStateChange();
		}
		
		this._mRequest.send(null);
	}
	else{
		// If busy try a little later.
		oTemp = this;
		this._mTimeoutId = setTimeout('oTemp.sendRequest(\'' + sUrlParams + '\')', 500);
	}
}


/**
 * @class Represents a command on the server. Communicates syncronously.
 * @extends Command
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 */
function SyncCommand(oSession, oConsole, oRequest){
	// Call the parent constructor.
	Command.call(this, oSession, oConsole, oRequest);
}

/**
* Inherit the Command class methods.
*/
SyncCommand.prototype = new Command();

/**
* Send the request to the server.
* @param {String} sUrlParams The query string with the params to send.
*/
SyncCommand.prototype.sendRequest = function(sUrlParams){
	sUrlParams = Url.addUrlParam(sUrlParams, 'type', 'xml');
	this._mRequest.open('GET', sUrlParams, false);
	try{
		this._mRequest.send(null);
	} catch(e){
		this._mConsole.displayError("FATAL ERROR: Connection lost. " + e.toString());
	}
	this.handleRequestStateChange();
}


/**
 * @class Controls the how the form should behave regarding its actual state.
 * @constructor
 * @param {Integer} iStatus
 */
function StateMachine(iStatus){
	/**
	 * Holds the actual status of the form. 0 = edit, 1 = idle.
	 * @type Integer
	 */
	this._mStatus = iStatus;
}

/** 
 * Set the focus on the element with the provided id.
 * @param {Variant} xValue Can be the element id as string or the HtmlElement object.
 */
StateMachine.setFocus = function(xValue){
	var oElement = ((typeof xValue) == 'string') ? document.getElementById(xValue) : xValue;
	
	if(oElement.tagName == 'INPUT' && oElement.value != '')
		TextRange.selectRange(oElement, 0, oElement.value.length);
	
	oElement.focus();
}

/** 
 * Set the form to edit state.
 * @param {String} sElementId The id of the element which receives the focus.
 */
StateMachine.prototype.changeToEditState = function(sElementId){
	// Disabled and enabled the required widgets in the form.
	var arrElements = document.getElementsByName('form_widget');
	for (var i = 0; i < arrElements.length; i++){
		var oElement = arrElements[i];
		
		if(oElement.tagName == 'A')
			oElement.className = 'invisible';
		else
			if(oElement.disabled == true)
				oElement.disabled = false;
			else
				oElement.disabled = true;
	}
	
	// If there is a details table.
	var oDetails = document.getElementById('details');
	if(oDetails){
		oTable = oDetails.getElementsByTagName('table')[0];
		oTable.className = '';
	}
	
	// Hide back link option.
	var oLink = document.getElementById('back_link');
	oLink.className = 'hidden';
	
	// Change the status_label to edit.
	var oStatus = document.getElementById('status_label');
	oStatus.innerHTML = 'Editando...';
	
	StateMachine.setFocus(sElementId);
	
	this._mStatus = 0;
}

/**
 * Set the form to cancel state.
 */
StateMachine.prototype.changeToCancelState = function(){
	var oButton = document.getElementById('cancel');
	oButton.disabled = true;
	
	var oStatus = document.getElementById('status_label');
	oStatus.className = 'cancel_status';
	oStatus.innerHTML = 'Anulado';
}

/**
 * Returns the actual value of the status property.
 * @returns {Integer}
 */
StateMachine.prototype.getStatus = function(){
	return this._mStatus;
}


/**
 * @class Removes the object in use from the session on the server.
 * @extends AsyncCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 */
function RemoveSessionObjectCommand(oSession, oConsole, oRequest, sKey){
	// Call the parent constructor.
	AsyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the key of the session object.
	 * @type String
	 */
	this._mKey = sKey;
	
	/**
	 * Holds the command name on the server.
	 * @type String
	 */
	this._mCmd = 'remove_session_object';
}

/**
* Inherit the AsyncCommand class methods.
*/
RemoveSessionObjectCommand.prototype = new AsyncCommand();

/**
* Executes the command.
*/
RemoveSessionObjectCommand.prototype.execute = function(){
	var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmd);
	str = Url.addUrlParam(str, 'key', this._mKey);
	this.sendRequest(str);
}