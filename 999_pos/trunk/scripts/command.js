/**
 * Library with the abstract base class for all command derived classes.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function for creating the request object and setting the console for displaying the results.
 * @param Console oConsole
 */
function Command(oConsole, oRequest){
	/**
	  * Holds the console where to display the results.
	  * @var Console
	  */
	 this.console = oConsole;
	
	/**
	 * Holds the request object.
	 * @var XmlHttpRequest
	 */
	 this.request = oRequest;
}

/**
 * Read the server response.
 */
Command.prototype.readResponse = function (){
	var xmlResponse = this.request.responseXML;
	
	// Potential errors with IE and Opera
	if(!xmlResponse || !xmlResponse.documentElement)
		throw(this.request.responseText);
	
	// Potential erros with Firefox
	var rootNodeName = xmlResponse.documentElement.nodeName;
	if(rootNodeName == 'parsererror')
		throw(this.request.responseText);
	
	var xmlDoc = xmlResponse.documentElement;
	
	// Look if there was an error response.
	var error = xmlDoc.getElementsByTagName('error');
	if(error){
		var msg = xmlDoc.getElementsByTagName('message')[0].firstChild.data;
		throw(msg);
	}
	
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
	if(this.request.readyState == 4){
		// Continue only if HTTP status is OK.
		if(this.request.status == 200){
			try{
				this.readResponse();
			}
			catch(e){
				this.console.displayError(e.toString());
			}
		}
		else
			this.console.displayError(this.request.statusText);
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