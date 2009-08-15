/**
 * Library with the details base class for all table elements.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 * @param Session oSession
 * @param Console oConsole
 * @param Request oRequest
 * @param string sKey
 * @param string sCmd
 * @param string sTableId
 */
function Details(oSession, oConsole, oRequest, sKey){
	// Call the parent constructor.
	SyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the key of the session object.
	 * @var string
	 */
	this._mKey = sKey;
	
	/**
	 * Holds the object div which holds the dynamic data.
	 * @var string
	 */
	this._mDiv = null;
	
	/**
	 * Holds the XSLT document.
	 * @var object
	 */
	this._mStylesheetDoc = null;
}

/**
* Inherit the Sync command class methods.
*/
Details.prototype = new SyncCommand();

/**
 * Test if the browser supports XSLT functionality.
 * @param string sUrlXsltFile
 */
Details.prototype.init = function(sUrlXsltFile, sDivId){
	this._mDiv = document.getElementById(sDivId);
	
	// load the file from the server
	this._mRequest.open("GET", sUrlXsltFile, false);        
	this._mRequest.send(null);
	
	try{
		// try to load the XSLT document
		if (window.DOMParser) // browsers with native functionality
		{
			var dp = new DOMParser();
			this._mStylesheetDoc = dp.parseFromString(this._mRequest.responseText, "text/xml");
		}
		else if (window.ActiveXObject) // Internet Explorer? 
		{
			this._mStylesheetDoc = this.createMsxml2DOMDocumentObject();         
			this._mStylesheetDoc.async = false;         
			this._mStylesheetDoc.load(this._mRequest.responseXML);
		}
	}
	catch(e){
		// if browser functionality failed, alert the user
		this._mConsole.displayError('Interno: Navegador no soporta funcionalidad XSLT.');
	}
}

/**
 * IE specific routine to create the MSXML object.
 * @return MSXML
 */
Details.prototype.createMsxml2DOMDocumentObject = function(){
	var msxml2DOM; 
	
	// MSXML versions that can be used for our grid
	var msxml2DOMDocumentVersions = new Array("Msxml2.DOMDocument.6.0",
                                            "Msxml2.DOMDocument.5.0",
                                            "Msxml2.DOMDocument.4.0");
	// try to find a good MSXML object
	for (var i=0; i<msxml2DOMDocumentVersions.length && !msxml2DOM; i++) 
	{
		try 
		{ 
			// try to create an object
			msxml2DOM = new ActiveXObject(msxml2DOMDocumentVersions[i]);
		} 
		catch (e) {}
	}
	// return the created object or display an error message
	if (!msxml2DOM)
		this._mConsole.displayError("Interno: Por favor actualize su version MSXML en \n" + 
          "http://msdn.microsoft.com/XML/XMLDownloads/default.aspx");
	else 
		return msxml2DOM;
}

/**
* Method for displaying success.
* @param DocumentElement xmlDoc
*/
Details.prototype.displaySuccess = function(xmlDoc){
	var xmlResponse = this._mRequest.responseXML;
	
	// browser with native functionality?    
    if (window.XMLHttpRequest && window.XSLTProcessor && 
        window.DOMParser)
    {      
    	// load the XSLT document
    	var xsltProcessor = new XSLTProcessor();
    	xsltProcessor.importStylesheet(this._mStylesheetDoc);
    	// generate the HTML code for the new page of products
    	page = xsltProcessor.transformToFragment(xmlResponse, document);
    	// display the page of products
    	this._mDiv.innerHTML = "";
    	this._mDiv.appendChild(page);
    } 
    // Internet Explorer code
    else if (window.ActiveXObject) 
    {
    	// load the XSLT document
    	var theDocument = this.createMsxml2DOMDocumentObject();
    	theDocument.async = false;
    	theDocument.load(xmlResponse);
    	// display the page of products
    	this._mDiv.innerHTML = theDocument.transformNode(this._mStylesheetDoc);
    }
}