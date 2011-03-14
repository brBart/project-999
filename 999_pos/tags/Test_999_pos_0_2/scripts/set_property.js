/**
 * @fileOverview Library with the SetPropertyCommand class.
 * @author Roberto Oliveros
 */

/**
 * @class Sets the desired property on an object on the server.
 * @extends Command
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 * @param {String} sKey
 */
function SetPropertyCommand(oSession, oConsole, oRequest, sKey){
	// Call the parent constructor.
	Command.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the key of the session object.
	 * @type String
	 */
	this._mKey = sKey;
	
	/**
	 * Queue for storing the future requests in case the request object is busy.
	 * @type Array
	 */
	this._mRequestQueue = new Array();
}

/**
 * Inherit the Command class methods.
 */
SetPropertyCommand.prototype = new Command();

/**
 * Executes the command.
 * @param {String} sCmd The name of the command to execute on the server.
 * @param {String} sValue The value of the property.
 * @param {String} sElementId The id of the input element.
 */
SetPropertyCommand.prototype.execute = function(sCmd, sValue, sElementId){
	if(sCmd == '' || sElementId == '')
		this._mConsole.displayError('Interno: Argumentos sCmd y sElementId inv&aacute;lidos.');
	else{
		var str = Url.addUrlParam(Url.getUrl(), 'cmd', sCmd);
		str = Url.addUrlParam(str, 'key', this._mKey);
		str = Url.addUrlParam(str, 'value', sValue);
		str = Url.addUrlParam(str, 'element_id', sElementId);
		this._mRequestQueue.push(str);
		this.sendRequest();
	}
}

/**
 * Send the values to the server.
 */
SetPropertyCommand.prototype.sendRequest = function(){
	// Continue only if the request is not busy or the queue is not empty.
	if((this._mRequest.readyState == 4 || this._mRequest.readyState == 0) && this._mRequestQueue.length > 0){
		var queueEntry = this._mRequestQueue.shift();
		var urlParams = Url.addUrlParam(queueEntry, 'type', 'xml');
		this._mRequest.open('GET', urlParams, true);
		
		// Necessary for lexical closure, because of the onreadystatchange call.
		var oCommand = this;
		this._mRequest.onreadystatechange = function(){
			oCommand.handleRequestStateChange();
		}
		this._mRequest.send(null);
	}
}
 
/**
 * Read the server response.
 */
SetPropertyCommand.prototype.readResponse = function(){
	// Call the parents function first.
	Command.prototype.readResponse.call(this);
	this.sendRequest();
}

/**
 * Cleans any previous failures.
 * @param {DocumentElement} xmlDoc
 */
SetPropertyCommand.prototype.displaySuccess = function(xmlDoc){
	var elementId = xmlDoc.getElementsByTagName('element_id')[0].firstChild.data;
	this._mConsole.cleanFailure(elementId);
}

/**
 * Displays the message on the console and points out the input element.
 * @param {DocumentElement} xmlDoc
 * @param {String} strMsg
 */
SetPropertyCommand.prototype.displayFailure = function(xmlDoc, strMsg){
	var elementId = xmlDoc.getElementsByTagName('element_id')[0].firstChild.data;
	
	// Must clean it in case a failure has been already display for the same element.
	this._mConsole.cleanFailure(elementId);
	this._mConsole.displayFailure(strMsg, elementId);
}