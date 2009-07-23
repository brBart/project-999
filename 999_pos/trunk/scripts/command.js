/**
 * Library with the abstract base class for all command derived classes.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function for creating the request object and setting the console for displaying the results.
 * @param Console oConsole
 */
function Command(oSession, oConsole, oRequest){
	/**
	 * Holds the session tracker.
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