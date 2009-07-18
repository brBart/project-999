/**
 * Library with the abstract base class for all command derived classes.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function for creating the request object and setting the console for displaying messages.
 * @param Console oConsole
 */
function Command(oConsole){
	/**
	 * Holds the request object.
	 * @var XmlHttpRequest
	 */
	 this.request = this.createXmlHttpRequestObject();
	 this.console = oConsole;
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
	  		displayError("Error creating the XMLHttpRequest object.");
	  	else 
	  		return xmlHttp;
}

/**
 * Handles the server response.
 */
Command.prototype.readResponse = function (){
	var xmlResponse = this.request.responseXML;
	
	// Potential errors with IE and Opera
	if(!xmlResponse || !xmlResponse.documentElement){
		this.console.displayError(this.request.responseText);
		return;
	}
	
	// Potential erros with Firefox
	var rootNodeName = this.request.documentElement.nodeName;
	if(rootNodeName == 'parsererror'){
		this.console.displayError(this.request.responseText);
		return;
	}
	
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
Command.prototype.displayFailure = function(xmlDoc, msg){
	return 0;
}