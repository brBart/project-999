/**
 * Library with the abstract base class for all command derived classes.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function for creating the request object and setting the console for displaying the results.
 * @param Console oConsole
 */
function Command(oConsole){
	/**
	  * Holds the console where to display the results.
	  * @var Console
	  */
	 this.console = oConsole;
	
	/**
	 * Holds the request object.
	 * @var XmlHttpRequest
	 */
	 this.request = this.createXmlHttpRequestObject();
}

/**
 * Creates the adequate object depending on the browser.
 * @return XmlHttpRequest 
 */
Command.prototype.createXmlHttpRequestObject = function(){
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
	  		// assume IE6 or older
	  		var XmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0",
	  					"MSXML2.XMLHTTP.5.0",
	                    "MSXML2.XMLHTTP.4.0",
	                    "MSXML2.XMLHTTP.3.0",
	                    "MSXML2.XMLHTTP",
	                    "Microsoft.XMLHTTP");
	  		// try every id until one works
	  		for (var i=0; i<XmlHttpVersions.length && !xmlHttp; i++) 
	  		{
	  			try 
	  			{ 
	  				// try to create XMLHttpRequest object
	  				xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
	  			} 
	  			catch (e) {} // ignore potential error
	  		}
	  	}
	  	// return the created object or display an error message
	  	if (!xmlHttp)
	  		this.console.displayError('Interno: Imposible crear el objeto XmlHttpRequest.');
	  	else 
	  		return xmlHttp;
}

/**
 * Read the server response.
 */
Command.prototype.readResponse = function (){
	var xmlResponse = this.request.responseXML;
	
	// Potential errors with IE and Opera
	if(!xmlResponse || !xmlResponse.documentElement){
		throw('Interno: ' + this.request.responseText);
	
	// Potential erros with Firefox
	var rootNodeName = this.request.documentElement.nodeName;
	if(rootNodeName == 'parsererror'){
		throw('Interno: ' + this.request.responseText);
	
	var xmlDoc = xmlResponse.documentElement;
	var success = xmlDoc.getElementByTagName('success').firstChild.data;
	if(success == 1)
		this.displaySuccess(xmlDoc);
	else{
		var msg = xmlDoc.getElementByTagName('message').firstChild.data;
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
				this.console.displayError('Interno: ' + e.toString());
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