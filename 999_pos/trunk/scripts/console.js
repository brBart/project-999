/**
 * Library with the Console class for displaying messages.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 */
function Console(){
	this._mElementDiv = document.getElementById('console');
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
	var newP = '<p id="failed_' + sElementId + '">' + sMsg + '</p>';
	var oElement = document.getElementById(sElementId + '_failed');
	
	// Display the message and indicate which property cause the failure.
	this.displayMessage(newP);
	oElement.className = 'error';
}

/**
* For displaying the errors.
* @param string sMsg
*/
Console.prototype.displayError = function(sMsg){
	var newP = '<p class="error">' + sMsg + '</p>';
	// Display the error message.
	this.displayMessage(newP);
}