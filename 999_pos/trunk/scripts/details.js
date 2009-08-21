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
 * @param StateMachine oMachine
 */
function Details(oSession, oConsole, oRequest, sKey, oMachine){
	// Call the parent constructor.
	SyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the key of the session object.
	 * @var string
	 */
	this._mKey = sKey;
	
	/**
	 * Holds the form state machine.
	 * @var StateMachine
	 */
	this._mMachine = oMachine;
	
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
	
	/**
	 * Holds the total received in the table.
	 * @var integer
	 */
	this._mTotalItems = 0;
	
	/**
	 * Keeps track of the selected row.
	 * @var integer
	 */
	this._mSelectedRow = 0;
	
	/**
	 * Holds the element widget before the table.
	 * @var object
	 */
	this._mPrevWidget = null;
	
	/**
	 * Holds the element widget next to the table.
	 * @var object
	 */
	this._mNextWidget = null;
}

/**
* Inherit the Sync command class methods.
*/
Details.prototype = new SyncCommand();

/**
 * Test if the browser supports XSLT functionality.
 * @param string sUrlXsltFile
 * @param object oDiv
 * @param object oPrevWidget
 * @param object oNextWidget
 */
Details.prototype.init = function(sUrlXsltFile, oDiv, oPrevWidget, oNextWidget){
	this._mDiv = oDiv;
	
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
			this._mStylesheetDoc = this.createMsxml2FreeThreadedDOMDocument();         
			this._mStylesheetDoc.async = false;         
			this._mStylesheetDoc.load(this._mRequest.responseXML);
		}
	}
	catch(e){
		// if browser functionality failed, alert the user
		this._mConsole.displayError('Interno: Navegador no soporta funcionalidad XSLT.');
	}
	
	// Set the previous and next widgets from the table element for controlling the tab sequence.
	oTemp = this;
	oPrevWidget.onkeydown = function(oEvent){
		oTemp.handlePrevTabKey(oEvent);
	}
	oNextWidget.onkeydown = function(oEvent){
		oTemp.handleNextTabKey(oEvent);
	}
	
	this._mPrevWidget = oPrevWidget;
	this._mNextWidget = oNextWidget;
}

/**
 * IE specific routine to create the MSXML DOMDocument object.
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
 * IE specific routine to create the MSXML FreeThreadedDOMDocument object.
 * @return MSXML
 */
Details.prototype.createMsxml2FreeThreadedDOMDocument = function(){
	var msxml2FreeDOM; 
 	
 	// MSXML versions that can be used for our grid
	var msxml2FreeDOMDocumentVersions = new Array("Msxml2.FreeThreadedDOMDocument.6.0",
                                 			"Msxml2.FreeThreadedDOMDocument.5.0",
                                 			"Msxml2.FreeThreadedDOMDocument.4.0");
	// try to find a good MSXML object
	for (var i=0; i<msxml2FreeDOMDocumentVersions.length && !msxml2FreeDOM; i++) 
	{
		try 
		{ 
			// try to create an object
			msxml2FreeDOM = new ActiveXObject(msxml2FreeDOMDocumentVersions[i]);
		} 
		catch (e) {}
	}
	// return the created object or display an error message
	if (!msxml2FreeDOM)
		this._mConsole.displayError("Interno: Por favor actualize su version MSXML en \n" + 
				"http://msdn.microsoft.com/XML/XMLDownloads/default.aspx");
 	else 
 		return msxml2FreeDOM;
}

/**
* IE specific routine to create the MSXML XSLTemplate object.
* @return MSXML
*/
Details.prototype.createMsxml2XSLTemplate = function(){
	var msxml2XSL; 
	
	// MSXML versions that can be used for our grid
	var msxml2XSLTemplateVersions = new Array("Msxml2.XSLTemplate.6.0",
                                           "Msxml2.XSLTemplate.5.0",
                                           "Msxml2.XSLTemplate.4.0");
	// try to find a good MSXML object
	for (var i=0; i<msxml2XSLTemplateVersions.length && !msxml2XSL; i++) 
	{
		try 
		{ 
			// try to create an object
			msxml2XSL = new ActiveXObject(msxml2XSLTemplateVersions[i]);
		} 
		catch (e) {}
	}
	// return the created object or display an error message
	if (!msxml2XSL)
		this._mConsole.displayError("Interno: Por favor actualize su version MSXML en \n" + 
         "http://msdn.microsoft.com/XML/XMLDownloads/default.aspx");
	else 
		return msxml2XSL;
}

/**
* Method for displaying success.
* @param DocumentElement xmlDoc
*/
Details.prototype.displaySuccess = function(xmlDoc){
	// Obtain the value of the total_items parameter.
	this._mTotalItems = xmlDoc.getElementsByTagName('total_items')[0].firstChild.data;
	
	var xmlResponse = this._mRequest.responseXML;
	// browser with native functionality?    
    if (window.XMLHttpRequest && window.XSLTProcessor && 
        window.DOMParser)
    {      
    	// load the XSLT document
    	var xsltProcessor = new XSLTProcessor();
    	xsltProcessor.importStylesheet(this._mStylesheetDoc);
    	xsltProcessor.setParameter(null, 'status', this._mMachine.getStatus());
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
    	
    	// create the template
    	var oTemplate = this.createMsxml2XSLTemplate();
    	oTemplate.stylesheet = this._mStylesheetDoc;
    	
    	// create the processor and set the form status
    	var xsltProcessor = oTemplate.createProcessor();
    	xsltProcessor.input = theDocument;
    	xsltProcessor.addParameter('status', this._mMachine.getStatus());
    	xsltProcessor.transform();
    	
    	// display the page of products
    	this._mDiv.innerHTML = xsltProcessor.output;
    }
}

/**
 * Controls if the tab key was pressed on the widget to set focus on the table.
 * @param Event oEvent
 */
Details.prototype.handlePrevTabKey = function(oEvent){
	oEvent = (!oEvent) ? window.event : oEvent;
	code = (oEvent.keyCode) ? oEvent.keyCode :
			((oEvent.which) ? oEvent.which : 0);
	
	// If tab key was pressed.
	if(this._mTotalItems > 0 && (code == 9 && !oEvent.shiftKey)){
		this.moveFirst();
		this.startListening();
		oEvent.cancelBubble = true;
	}
}

/**
* Controls if the shift + tab keys were pressed on the which to set focus on the table.
* @param Event oEvent
*/
Details.prototype.handleNextTabKey = function(oEvent){
	oEvent = (!oEvent) ? window.event : oEvent;
	code = (oEvent.keyCode) ? oEvent.keyCode :
			((oEvent.which) ? oEvent.which : 0);
	
	// If shift + tab keys were pressed.
	if(this._mTotalItems > 0 && (oEvent.shiftKey && code == 9)){
		this.moveFirst();
		this.startListening();
		oEvent.cancelBubble = true;
	}
}

/**
 * Handles the key down press event.
 * @param Event oEvent
 */
Details.prototype.handleKeyDown = function(oEvent){
	oEvent = (!oEvent) ? window.event : oEvent;
	code = (oEvent.keyCode) ? oEvent.keyCode :
			((oEvent.which) ? oEvent.which : 0);
	
	// If the up arrow was pressed.
	if(code == 38)
		this.movePrevious();
	
	// If the down arrow was pressed.
	if(code == 40)
		this.moveNext();
	
	// If the tab key was pressed.
	if(code == 9){
		this.deselectRow();
		this.stopListening();
		
		//Cancel default behaviour, also detect if it is FF.
		if(document.addEventListener)
			oEvent.preventDefault();
		else
			oEvent.returnValue = false;
		
		// If the shift key was pressed.
		if(oEvent.shiftKey)
			this._mPrevWidget.focus();
		else
			this._mNextWidget.focus();
	}
}

/**
 * Method that controls the key down for the table element.
 */
Details.prototype.startListening = function(){
	oTemp = this;
	document.onkeydown = function(oEvent){
		oTemp.handleKeyDown(oEvent);
	}
}
 
/**
 * Method that removed the event handlers for the table.
 */
Details.prototype.stopListening = function(){
 	document.onkeydown = null;
}
 
/**
 * Select the row in the iPos position.
 * @param integer iPos
 */
Details.prototype.selectRow = function(iPos){
	// If already selected.
	if(this._mSelectedRow == iPos)
		return;
	
	newTr = document.getElementById('tr' + iPos);
	newTr.className = 'hightlightrow';
	
	if(this._mSelectedRow > 0){
		oldTr = document.getElementById('tr' + this._mSelectedRow);
		oldTr.className = (this._mSelectedRow % 2 == 0) ? 'even' : '';
	}
	
	this._mSelectedRow = iPos;
}
 
/**
 * Remove focus from the actual selected row.
 */
Details.prototype.deselectRow = function(){
	oldTr = document.getElementById('tr' + this._mSelectedRow);
	oldTr.className = (this._mSelectedRow % 2 == 0) ? 'even' : '';
	this._mSelectedRow = 0;
}
 
/**
 * Selects the first row.
 */
Details.prototype.moveFirst = function(){
	if(this._mTotalItems > 0)
		this.selectRow(1);
}

/**
 * Selects the previous row.
 */
Details.prototype.movePrevious = function(){
	if(this._mTotalItems > 0 && this._mSelectedRow > 1)
		this.selectRow(this._mSelectedRow - 1);
}

/**
 * Selects the next row.
 */
Details.prototype.moveNext = function(){
	if(this._mTotalItems > 0 && this._mSelectedRow < this._mTotalItems)
		this.selectRow(this._mSelectedRow + 1);
}