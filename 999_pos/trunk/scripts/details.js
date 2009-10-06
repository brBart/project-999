/**
 * @fileOverview Library with the Details base class.
 * @author Roberto Oliveros
 */

/**
 * @class Manages how to display and control an object's details.
 * @extends SyncCommand
 * @constructor
 * @param {Session} oSession
 * @param {Console} oConsole
 * @param {XmlHttpRequest} oRequest
 * @param {String} sKey
 * @param {StateMachine} oMachine
 * @param {EventDelegator} oEventDelegator
 */
function Details(oSession, oConsole, oRequest, sKey, oMachine, oEventDelegator){
	// Call the parent constructor.
	SyncCommand.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Flag that indicates if a row on the details element was clicked.
	 * @type Boolean
	 */
	this.mWasClicked = false;
	
	/**
	 * Holds the delete function to executed in case of deletion.
	 * @type Object
	 */
	this.mDeleteFunction = null;
	
	/**
	 * Holds the key of the session object.
	 * @type String
	 */
	this._mKey = sKey;
	
	/**
	 * Holds the form's state machine.
	 * @type StateMachine
	 */
	this._mMachine = oMachine;
	
	/**
	 * Holds the object in charge of handling the click events.
	 * @type EventDelegator
	 */
	this._mEventDelegator = oEventDelegator;
	
	/**
	 * Holds a reference to the div element which holds the details data.
	 * @type HtmlElement
	 */
	this._mDiv = null;
	
	/**
	 * Holds the XSLT document.
	 * @type XsltDocument
	 */
	this._mStylesheetDoc = null;
	
	/**
	 * Holds the total items received.
	 * @type Integer
	 */
	this._mPageItems = 0;
	
	/**
	 * Keeps track of the selected row.
	 * @type Integer
	 */
	this._mSelectedRow = 0;
	
	/**
	 * Holds the input element which stands previous to the details element.
	 * @type HtmlElement
	 */
	this._mPrevWidget = null;
	
	/**
	 * Holds the input element next to the table element.
	 * @type HtmlElement
	 */
	this._mNextWidget = null;
	
	/**
	 * Flag that indicates if the object is listening.
	 * @type Boolean
	 */
	this._mHasFocus = false;
	
	/**
	 * Holds the id of the actual selected detail (row) element.
	 * @type String
	 */
	this._mDetailId = '';
	
	/**
	 * Holds the name of the variable which holds the instance of this class on the html document.
	 * @type String
	 */
	this._mDetailsObj = '';
	
	/**
	 * Holds the name of the variable which holds the object in charge of the deleting a detail.
	 * @type String
	 */
	this._mDeleteObj = '';
	
	/**
	 * Holds the name of the command to use on the server for deleting a detail.
	 * @type String
	 */
	this._mDeleteCmd = '';
	
	/**
	 * Holds the actual page number.
	 * @type Integer
	 */
	this._mPage = 0;
}

/**
 * Inherit the Sync command class methods.
 */
Details.prototype = new SyncCommand();

/**
 * Sets the necessary variables and test if the browser supports XSLT functionality.
 * @param {String} sUrlXsltFile Path to the xslt file on the server.
 * @param {String} sDiv The id of the div element which holds the details data.
 * @param {String} sPrevWidget The id of the input element previous to the details div element.
 * @param {String} sNextWidget The id of the input element next to the details div element.
 * @param {String} sDetailsObj Name of the variable on the html document which holds the instance of this class.
 * @param {String} sDeleteObj Name of the variable which holds the object in charge of the deleting a detail.
 * @param {String} sDeleteCmd Name of the command to use on the server for deleting a detail.
 */
Details.prototype.init = function(sUrlXsltFile, sDiv, sDetailsObj, sPrevWidget, sNextWidget, sDeleteObj,
		sDeleteCmd){
	// Register with the event delegator.
	this._mEventDelegator.registerObject(this);
	
	this._mDiv = document.getElementById(sDiv);
	// Set only if the arguments are passed.
	if(sPrevWidget != null && sNextWidget != null){
		this._mPrevWidget = document.getElementById(sPrevWidget);
		this._mNextWidget = document.getElementById(sNextWidget);
		
		// Set the previous and next widgets from the table element for controlling the tab sequence.
		var oTemp = this;
		this._mPrevWidget.onkeydown = function(oEvent){
			oTemp.handlePrevTabKey(oEvent);
		}
		this._mNextWidget.onkeydown = function(oEvent){
			oTemp.handleNextTabKey(oEvent);
		}
	}
	
	// Set for use in the xslt processor.
	this._mDetailsObj = sDetailsObj;
	// Set only if the arguments are passed.
	this._mDeleteObj = (sDeleteObj == null) ? '' : sDeleteObj;
	this._mDeleteCmd = (sDeleteCmd == null) ? '' : sDeleteCmd;
	
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
}

/**
 * IE specific routine to create the MSXML DOMDocument object.
 * @returns {MSXMLDOMDocument}
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
 * @returns {MSXMLFreeThreadedDOMDocument}
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
 * @returns {MSXMLTemplate}
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
 * @param {DocumentElement} xmlDoc
 */
Details.prototype.displaySuccess = function(xmlDoc){
	// Obtain the page number.
	this._mPage = parseInt(xmlDoc.getElementsByTagName('page')[0].firstChild.data);
	// Obtain the value of the page_items parameter.
	this._mPageItems = xmlDoc.getElementsByTagName('page_items')[0].firstChild.data;
	// Reset selected row position and focus.
	this._mSelectedRow = 0;
	this._mHasFocus = false;
	
	var xmlResponse = this._mRequest.responseXML;
	// browser with native functionality?    
    if (window.XMLHttpRequest && window.XSLTProcessor && 
        window.DOMParser)
    {      
    	// load the XSLT document
    	var xsltProcessor = new XSLTProcessor();
    	xsltProcessor.importStylesheet(this._mStylesheetDoc);
    	
    	// Set parameters.
    	xsltProcessor.setParameter(null, 'status', this._mMachine.getStatus());
    	xsltProcessor.setParameter(null, 'details_obj', this._mDetailsObj);
    	xsltProcessor.setParameter(null, 'delete_obj', this._mDeleteObj);
    	xsltProcessor.setParameter(null, 'delete_cmd', this._mDeleteCmd);
    	
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
    	
    	// Set parameters.
    	xsltProcessor.addParameter('status', this._mMachine.getStatus());
    	xsltProcessor.addParameter('details_obj', this._mDetailsObj);
    	xsltProcessor.addParameter('delete_obj', this._mDeleteObj);
    	xsltProcessor.addParameter('delete_cmd', this._mDeleteCmd);
    	
    	xsltProcessor.transform();
    	// display the page of products
    	this._mDiv.innerHTML = xsltProcessor.output;
    }
}

/**
 * Controls if the tab key was pressed on the previous input element to set focus on the details element.
 * @param {Event} oEvent
 */
Details.prototype.handlePrevTabKey = function(oEvent){
	oEvent = (!oEvent) ? window.event : oEvent;
	code = (oEvent.keyCode) ? oEvent.keyCode :
			((oEvent.which) ? oEvent.which : 0);
	
	// If tab key was pressed and state machine is in edit status.
	if(code == 9 && !oEvent.shiftKey){
		this.moveFirst();
		this.setFocus();
		// Stop propagation because the document is already listening.
		oEvent.cancelBubble = true;
		
		// Cancel default behaviour (which is tab foward), also detect if it is FF.
		if(document.addEventListener)
			oEvent.preventDefault();
		else
			oEvent.returnValue = false;
		
		// Remove focus from the previous widget.
		this._mPrevWidget.blur();
	}
}

/**
 * Controls if the shift + tab keys were pressed on the next element to set focus on the details element.
 * @param {Event} oEvent
 */
Details.prototype.handleNextTabKey = function(oEvent){
	oEvent = (!oEvent) ? window.event : oEvent;
	code = (oEvent.keyCode) ? oEvent.keyCode :
			((oEvent.which) ? oEvent.which : 0);
	
	// If shift + tab keys were pressed.
	if(oEvent.shiftKey && code == 9){
		this.moveFirst();
		this.setFocus();
		// Stop propagation because the document is already listening.
		oEvent.cancelBubble = true;
		
		// Cancel default behaviour (which is tab foward), also detect if it is FF.
		if(document.addEventListener)
			oEvent.preventDefault();
		else
			oEvent.returnValue = false;
		
		// Remove focus from the next widget.
		this._mNextWidget.blur();
	}
}

/**
 * Handles the key down press event on the details element.
 * @param {Event} oEvent
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
	
	// If the delete key was pressed.
	if(code == 46)
		if(this._mPageItems > 0 && this._mSelectedRow > 0)
			this.deleteDetail();
	
	// If the tab key was pressed.
	if(code == 9){
		this.deselectRow();
		this.loseFocus();
		
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
 * Handles click on all the details element area.
 * @param {Event} oEvent
 */
Details.prototype.detailsHandleClick = function(oEvent){
	this.mWasClicked = true;
}
 
/**
 * If anything but the details element area was click, remove focus.
 */
Details.prototype.blur = function(){
	if(this._mHasFocus){ 
		this.deselectRow();
		this.loseFocus();
	}
}

/**
 * Set focus on the details element.
 */
Details.prototype.setFocus = function(){
	// Check if already has focus and state machine status equal edit.
	if(!this._mHasFocus && this._mMachine.getStatus() == 0){
		var oTemp = this;
		
		document.onkeydown = function(oEvent){
			oTemp.handleKeyDown(oEvent);
		}
		
		oTable = this._mDiv.getElementsByTagName('table')[0];
		
		oTable.onclick = function(oEvent){
			oTemp.detailsHandleClick(oEvent);
		}
		
		oTable.className = 'has_focus';
		this._mHasFocus = true;
	}
}
 
/**
 * Removes the focus from the details element. Removes the event handlers from the element.
 */
Details.prototype.loseFocus = function(){
 	document.onkeydown = null;
 	
 	oTable = this._mDiv.getElementsByTagName('table')[0];
 	oTable.onclick = null;
 	
 	oTable.className = '';
 	this._mHasFocus = false;
}
 
/**
 * Select the row in the iPos position.
 * @param {Integer} iPos
 */
Details.prototype.selectRow = function(iPos){
	var newTr = document.getElementById('tr' + iPos);
	newTr.className = 'hightlightrow';
	
	if(this._mSelectedRow > 0 && this._mSelectedRow != iPos){
		var oldTr = document.getElementById('tr' + this._mSelectedRow);
		oldTr.className = (this._mSelectedRow % 2 == 0) ? 'even' : '';
	}
	
	this._mSelectedRow = parseInt(iPos);
	
	// Enable remove button and set the detail id property.
	this.setDetailId(newTr);
	oButton = document.getElementById('remove_detail');
	oButton.disabled = false;
}
 
/**
 * Remove focus from the actual selected row.
 */
Details.prototype.deselectRow = function(){
	if(this._mSelectedRow > 0){
		oldTr = document.getElementById('tr' + this._mSelectedRow);
		oldTr.className = (this._mSelectedRow % 2 == 0) ? 'even' : '';
		this._mSelectedRow = 0;
	}
	
	// Disable remove button.
	oButton = document.getElementById('remove_detail');
	oButton.disabled = true;
}
 
/**
 * Selects the first row.
 */
Details.prototype.moveFirst = function(){
	if(this._mPageItems > 0)
		this.selectRow(1);
}

/**
 * Selects the previous row.
 */
Details.prototype.movePrevious = function(){
	if(this._mPageItems > 0 && this._mSelectedRow > 1)
		this.selectRow(this._mSelectedRow - 1);
}

/**
 * Selects the next row.
 */
Details.prototype.moveNext = function(){
	if(this._mPageItems > 0 && this._mSelectedRow < this._mPageItems)
		this.selectRow(this._mSelectedRow + 1);
}
 
/**
 * Selects the last row.
 */
Details.prototype.moveLast = function(){
	this.selectRow(this._mPageItems);
}
 
/**
 * Select the row in the provided position, if it does not exists, move to the last position.
 * @param {Integer} iPos Position to move to.
 * @param {Integer} iPage Use to compare if the position stills in the same page.
 */
Details.prototype.moveTo = function(iPos, iPage){
	if(this._mPageItems > 0)
		if(iPos > this._mPageItems || this._mPage != iPage)
			this.moveLast();
		else
			this.selectRow(iPos);
}
 
/**
 * Select row when click on it.
 * @param {HtmlElement} oRow
 */
Details.prototype.clickRow = function(oRow){
	// State machine status has to be on edit mode.
	if(this._mMachine.getStatus() == 0){
		this.setFocus();
		this.selectRow(oRow.id.substring(2));
	}
}

/**
 * Sets the id of the detail.
 * @param {HtmlElement} oRow
 */
Details.prototype.setDetailId = function(oRow){
	oTd = oRow.getElementsByTagName('td')[0];
	this._mDetailId = oTd.id;
}
 
/**
 * Returns the detail id.
 * @returns {Integer}
 */
Details.prototype.getDetailId = function(){
	return this._mDetailId;
}

/**
 * Returns the selected row position.
 * @returns {Integer}
 */
Details.prototype.getPosition = function(){
	return this._mSelectedRow;
}
 
/**
 * Returns the actual page number.
 * @returns {Integer}
 */
Details.prototype.getPageNumber = function(){
 	return this._mPage;
}

/**
 * Abstract method.
 */
Details.prototype.deleteDetail = function(){
	 this.mDeleteFunction(this._mDeleteCmd);
}