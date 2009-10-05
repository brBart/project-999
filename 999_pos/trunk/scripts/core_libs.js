/**
 * Library with the main class for displaying and communicate information with the server. Console, Url,
 * createXmlHttpRequestObject, Command.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function for the Console class.
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
 
 
/**
  * Constructor for the Url class. Does nothing because methods are static.
  */
function Url(){}

/**
  * Method for returning the site url.
  */
Url.getUrl = function(){
 	return 'index.php';
}

/**
  * Concatenates the params the provided url.
  * @param string sUrl
  * @param string sParam
  * @param string sValue
  */
Url.addUrlParam = function(sUrl, sParam, sValue){
 	sUrl += (sUrl.indexOf('?') == -1) ? '?' : '&';
 	sUrl += encodeURIComponent(sParam) + '=' + encodeURIComponent(sValue);
 	return sUrl;
}


/**
* Function that creates the adequate object depending on the browser.
* @return XmlHttpRequest 
*/
function createXmlHttpRequestObject(){
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
* Constructor function for obtaining the request object and setting the console for displaying the results.
* @param Session oSession
* @param Console oConsole
* @param Request oRequest
*/
function Command(oSession, oConsole, oRequest){
	/**
	 * Holds the session tracker.
	 * @var Session
	 */
	this._mSession = oSession;
	
	/**
	  * Holds the console where to display the results.
	  * @var Console
	  */
	 this._mConsole = oConsole;
	
	/**
	 * Holds the request object.
	 * @var XmlHttpRequest
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
* @param DocumentElement xmlDoc
*/
Command.prototype.displaySuccess = function(xmlDoc){
	return 0;
}

/**
* Abstract function for displaying failure.
* @param DocumentElement xmlDoc
* @param string msg
*/
Command.prototype.displayFailure = function(xmlDoc, strMsg){
	return 0;
}