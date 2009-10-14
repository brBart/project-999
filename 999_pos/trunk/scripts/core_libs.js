/**
 * @fileOverview Library with the main class for display and communicate information with the server. Console, Url, createXmlHttpRequestObject, Command.
 * @author Roberto Oliveros
 */

/**
 * @class Controls how the messages are displayed to the user.
 * @constructor
 * @param {String} sConsole The name of the div element where the messages are displayed.
 */
function Console(sConsole){
	/**
	 * Holds the element div.
	 * @type HtmlElement
	 */
	this._mElementDiv = document.getElementById(sConsole);
	 
	/**
	 * Holds the info timeout id.
	 * @type Integer
	 */
	this._mInfoTimeoutId = 0;
}

/**
 * Displays the message in the div element.
 * @param {String} sMsg
 */
Console.prototype.displayMessage = function(sMsg){
	this._mElementDiv.innerHTML += sMsg;
	this._mElementDiv.scrollTop = this._mElementDiv.scrollHeight;
}

/**
 * For displaying any kind of failure on the console div and right next to the element.
 * @param {String} sMsg
 * @param {String} sElementId The id of the element.
 */
Console.prototype.displayFailure = function(sMsg, sElementId){
	var newP = '<p id="failed-' + sElementId + '" class="failure">' + sMsg + '</p>';
	var oElement = document.getElementById(sElementId + '-failed');
	
	// Display the message and indicate which property cause the failure.
	this.displayMessage(newP);
	oElement.className = 'failed';
}

/**
* Method for displaying the errors.
* @param {String} sMsg
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
 * Displays an info message for a short period of time.
 * @param {String} sMsg
 */
Console.prototype.displayInfo = function(sMsg){
	// Clear any timeout that was already waiting.
	if(this._mInfoTimeoutId != 0)
		clearTimeout(this._mTimeoutId);
	
	var elementP = document.getElementById('info');
	// If there was a message.
	if(elementP)
		this._mElementDiv.removeChild(elementP);
	
	var newP = '<p id="info" class="info">' + sMsg + '</p>';
	// Display the error message.
	this.displayMessage(newP);
	
	// Clean the info message in 12 seconds.
	oTemp1 = this;
	this._mInfoTimeoutId = setTimeout('oTemp1.cleanInfo()', 12000);
}

/**
 * Cleans an info message from the console.
 */
Console.prototype.cleanInfo = function(){
	var elementP = document.getElementById('info');
	this._mElementDiv.removeChild(elementP);
	this._mInfoTimeoutId = 0;
}

/**
 * Removes the failure message from the console and from the element that provoked.
 * @param {String} sElementId The id of the element.
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
 
 
/**
 * @class Contains the url to the server. Also helps for creating the queries strings. 
 */
function Url(){}

/** 
 * Method for returning the site url.
 * @returns {String}
 */
Url.getUrl = function(){
 	return 'index.php';
}

/**
 * Concatenates the params to the provided url.
 * @param {String} sUrl
 * @param {String} sParam
 * @param {String} sValue
 * @returns {String}
 */
Url.addUrlParam = function(sUrl, sParam, sValue){
 	sUrl += (sUrl.indexOf('?') == -1) ? '?' : '&';
 	sUrl += encodeURIComponent(sParam) + '=' + encodeURIComponent(sValue);
 	return sUrl;
}

 
/**
 * @class Responsible for creating the Http Requests objects.
 */
function Request(){}

/**
 * 
 * Method for creating the browser's XmlHttpRequest object.
 * @static
 * @returns {XmlHttpRequest} 
 */
Request.createXmlHttpRequestObject = function(){
	// will store the reference to the XMLHttpRequest object
	var xmlHttp;
	// this should work for all browsers except IE6 and older
	try
		{
	    	// try to create XMLHttpRequest object
	    	xmlHttp = new XMLHttpRequest();
		}
	  	catch(e)
	  	{
	  		oConsole.displayError('Interno: Imposible crear el objeto XmlHttpRequest. Verifique la version del' +
	  				'navegador.');
	  	}
	  	
 		return xmlHttp;
}


/**
 * @class Represents a command to execute on the server. 
 * @constructor Obtains the Request, Console objects and sets the Console for displaying the results.
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 */
function Command(oSession, oConsole, oRequest){
	/**
	 * Holds the session tracker.
	 * @type Session
	 */
	this._mSession = oSession;
	
	/**
	  * Holds the console where to display the results.
	  * @type Console
	  */
	 this._mConsole = oConsole;
	
	/**
	 * Holds the request object.
	 * @type XmlHttpRequest
	 */
	 this._mRequest = oRequest;
}

/**
* Read the server response.
*/
Command.prototype.readResponse = function (){
	var xmlResponse = this._mRequest.responseXML;
	
	// Potential errors with IE and Opera
	if(!xmlResponse || !xmlResponse.documentElement)
		throw('FATAL ERROR: ' + this._mRequest.responseText);
	
	// Potential erros with Firefox
	var rootNodeName = xmlResponse.documentElement.nodeName;
	if(rootNodeName == 'parsererror')
		throw('FATAL ERROR: ' + this._mRequest.responseText);
	
	var xmlDoc = xmlResponse.documentElement;
	
	// Look if there was an error response.
	var error = xmlDoc.getElementsByTagName('error');
	if(error.length > 0){
		var msg = xmlDoc.getElementsByTagName('message')[0].firstChild.data;
		throw(msg);
	}
	
	// Look if it was an logout message.
	var logout = xmlDoc.getElementsByTagName('logout');
	if(logout.length > 0){
		// Notify the session ended.
		this._mSession.setIsActive(false);
		var msg = xmlDoc.getElementsByTagName('message')[0].firstChild.data;
		throw(msg);
	}
	
	// It is a standard response.
	var success = xmlDoc.getElementsByTagName('success')[0].firstChild.data;
	if(success == 1)
		this.displaySuccess(xmlDoc);
	else{
		var msg = xmlDoc.getElementsByTagName('message')[0].firstChild.data;
		this.displayFailure(xmlDoc, msg);
	}
}

/**
* Handle the server response of the request.
*/
Command.prototype.handleRequestStateChange = function(){
	// When readyState is 4, read server response.
	if(this._mRequest.readyState == 4){
		// Continue only if HTTP status is OK.
		if(this._mRequest.status == 200){
			try{
				this.readResponse();
			}
			catch(e){
				this._mConsole.displayError(e.toString());
			}
		}
		else
			this._mConsole.displayError(this._mRequest.statusText);
	}
}

/**
* Abstract function for displaying success.
* @param {DocumentElement} xmlDoc The xml document with the information received from the server.
*/
Command.prototype.displaySuccess = function(xmlDoc){
	return 0;
}

/**
* Abstract function for displaying failure.
* @param {DocumentElement} xmlDoc The xml document with the information received from the server.
* @param {String} strMsg The message to display.
*/
Command.prototype.displayFailure = function(xmlDoc, strMsg){
	return 0;
}