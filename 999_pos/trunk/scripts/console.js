/**
 * Library with the Console class for displaying messages.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 * @param string sConsole
 */
function Console(sConsole){
	this._mElementDiv = document.getElementById(sConsole);
}

/**
 * Displays the message in the div element.
 */
Console.prototype.displayMessage = function(sMsg){
	this._mElementDiv.innerHTML += sMsg;
	this._mElementDiv.scrollTop = this._mElementDiv.scrollHeight;
}

/**
 * For displaying any kind of failure on the console div and right next to the element.
 * @param string sMsg
 * @param string sElementId
 */
Console.prototype.displayFailure = function(sMsg, sElementId){
	var newP = '<p id="failed-' + sElementId + '" class="failure">' + sMsg + '</p>';
	var oElement = document.getElementById(sElementId + '-failed');
	
	// Display the message and indicate which property cause the failure.
	this.displayMessage(newP);
	oElement.className = 'failed';
}

/**
* For displaying the errors.
* @param string sMsg
*/
Console.prototype.displayError = function(sMsg){
	var elementP = document.getElementById('error');
	// If there was a message.
	if(elementP)
		this._mElementDiv.removeChild(elementP);
	
	var newP = '<p id="error" class="error">' + sMsg + '</p>';
	// Display the error message.
	this.displayMessage(newP);
}

/**
 * Removes the failure message from the console and from the element that provoked.
 * @param string sElementId
 */
Console.prototype.cleanFailure = function(sElementId){
	var elementP = document.getElementById('failed-' + sElementId);
	
	// If there was a message.
	if(elementP){
		this._mElementDiv.removeChild(elementP);
		this._mElementDiv.scrollTop = this._mElementDiv.scrollHeight;
	
		var oElement = document.getElementById(sElementId + '-failed');
		oElement.className = 'hidden';
	}
}
 
/**
 * Clean the element div from all messages. Elements fields as well.
 */
Console.prototype.reset = function(){
	var arrElements = this._mElementDiv.getElementsByTagName('*');
	while(arrElements.length > 0){
		var sId = arrElements[0].getAttribute('id'); 
		if(sId != 'error')
			this.cleanFailure(sId.substring(sId.indexOf('-') + 1));
		else
			this._mElementDiv.removeChild(arrElements[0]);
	}
}