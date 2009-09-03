/**
 * Library with the SearchProduct command base class.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 * @param Session oSession
 * @param Console oConsole
 * @param Request oRequest
 */
function SearchProduct(oSession, oConsole, oRequest){
	// Call the parent constructor.
	Command.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the name of the command on the server.
	 * @var string
	 */
	this._mCmd = 'search_product';
	
	/**
	 * Holds the text input.
	 * @var object
	 */
	this._mTxtWidget = null;
	
	/**
	 * Holds the div containing the suggest table.
	 * @var object
	 */
	this._mScrollDiv = null;
	
	/**
	 * Holds the settimeout id.
	 * @var integer
	 */
	this._mTimeoutId = 0;
	
	/**
	 * Holds the setinterval id.
	 * @var integer
	 */
	this._mIntervalId = 0;
	
	/**
	 * Holds the last keyword for which suggests have been requested.
	 * @var string
	 */
	this.mUserKeyword = '';
	
	/**
	 * Holds the keyword for which an HTTP request has been initiated.
	 * @var string
	 */
	this._mHttpRequestKeyword = '';
	
	/**
	 * Cache containing the retrieved suggestions for different keywords.
	 * @var object
	 */
	this._mCache = null;
	
	/**
	 * Flag that indicates if the up or down keys were pressed the last time the keydown event occurred.
	 * @var boolean
	 */
	this._mIsKeyUpDownPressed = false;
	
	/**
	 * The last suggestion that has been used for autocompleting the keyword.
	 * @var string
	 */
	this._mAutocompletedKeyword = '';
	
	/**
	 * Number of suggestions received as results for the keyword.
	 * @var integer
	 */
	this._mSuggestions = 0;
	
	/**
	 * The currently selected suggestion (by arrow keys or mouse).
	 * @var integer
	 */
	this._mPosition = -1;
	
	/**
	 * The maximum number of characters to be displayed for a suggestion.
	 * @var integer
	 */
	this._mSuggestionMaxLength = 30;
	
	/**
	 * The minimum position of the visible suggestions.
	 * @var integer
	 */
	this._mMinVisiblePosition = 0;
	
	/**
	 * The maximum position of the visible suggestions.
	 * @var integer
	 */
	this._mMaxVisiblePosition = 9;
	
	/**
	 * Flag that indicates if the listening cycle has started.
	 * @var boolean
	 */
	this._mIsListening = false;
}

/**
* Inherit the Command class methods.
*/
SearchProduct.prototype = new Command();

/**
 * Sets the text input widget from where the user will input the search query.
 * @param object oTxtWidget
 */
SearchProduct.prototype.init = function(oTxtWidget){
	this._mTxtWidget = oTxtWidget;
	this._mScrollDiv = oTxtWidget.nextSibling.nextSibling;
	oTxtWidget.setAttribute('autocomplete', 'off');
	// Initiate the cache.
	this._mCache = new Object();
	
	oTemp = this;
	oTxtWidget.onkeydown = function(oEvent){
		oTemp.handleKeyDown(oEvent);
	}
	oTxtWidget.onfocus = function(){
		oTemp.startListening();
	}
}

/**
 * Start the timeout cycle for the checkForChanges method.
 */
SearchProduct.prototype.startListening = function(){
	if(!this._mIsListening){
		oDiv = document.getElementById('search_product');
			
		oDiv.onclick = function(oEvent){
			oTemp.divHandleClick(oEvent);
		}
		document.onclick = function(oEvent){
			oTemp.documentHandleClick(oEvent);
		}
		
		oTemp = this;
		this._mIntervalId = setInterval('oTemp.checkForChanges()', 500);
		this._mIsListening = true;
	}
}
 
/**
 * Clears the timeout cycle.
 */
SearchProduct.prototype.stopListening = function(){
	oDiv = document.getElementById('search_product');
	 	
 	oDiv.onclick = null;
 	document.onclick = null;
	 
	clearInterval(this._mIntervalId);
	this.hideSuggestions();
	this._mIsListening = false;
}

/**
 * Check if the user has change the text in the input text widget.
 */
SearchProduct.prototype.checkForChanges  = function(){
	// retrieve the keyword object 
	var keyword = this._mTxtWidget.value;
	// check to see if the keyword is empty
	if(keyword == '')
	{
	  // hide the suggestions
	  this.hideSuggestions();
	  // reset the keywords 
	  this.mUserKeyword = '';
	  this._mHttpRequestKeyword = '';
	  this._mPosition = -1;
	}
	
	// check to see if there are any changes
	if((this.mUserKeyword != keyword) && 
	   (this._mAutocompletedKeyword != keyword) && 
	   (!this._mIsKeyUpDownPressed))
	  // update the suggestions
	  this.getSuggestions(keyword);
}
  
/**
 * Method that checks to see if the keyword specified as parameter is in the cache or tries to find the
 * longest matching prefixes in the cache and adds them in the cache for the current keyword parameter.
 * @param string sKeyword
 */
SearchProduct.prototype.checkCache = function(sKeyword){
	// check to see if the sKeyword is already in the cache
	if(this._mCache[sKeyword])
		return true;
	// try to find the biggest prefixes
	for(i = sKeyword.length - 2; i >= 0; i--)
	{
		// compute the current prefix sKeyword 
		var currentKeyword = sKeyword.substring(0, i + 1);
 		// check to see if we have the current prefix sKeyword in the cache
 		if(this._mCache[currentKeyword])
 		{            
 			// the current keyword's results already in the cache
 			var cacheResults = this._mCache[currentKeyword];
 			// the results matching the sKeyword in the current cache results
 			var keywordResults = new Array();
 			var keywordResultsSize = 0;            
 			// try to find all matching results starting with the current prefix
 			for(j = 0; j < cacheResults.length; j++)
 			{
 				if(cacheResults[j].name.toUpperCase().indexOf(sKeyword.toUpperCase()) == 0)
 					keywordResults[keywordResultsSize++] = cacheResults[j];
 			}      
 			// add all the sKeyword's prefix results to the cache
 			this.addToCache(sKeyword, keywordResults);      
 			return true;  
 		}
 	}
 	// no match found
 	return false;
}

/**
 * Method that adds to a keyword an array of values.
 * @param string sKeyword
 * @param array values
 */
SearchProduct.prototype.addToCache = function(sKeyword, values){
	// create a new array entry in the cache
	this._mCache[sKeyword] = new Array();
 	// add all the values to the sKeyword's entry in the cache
 	for(i = 0; i < values.length; i++)
 		this._mCache[sKeyword][i] = values[i];
}
 
/**
 * Hide the div containing the suggestions.
 */
SearchProduct.prototype.hideSuggestions = function(){
	this._mScrollDiv.style.visibility = 'hidden';
}

/**
 * Returns a list of results from the keyword suggestions.
 * @param string sKeyword
 */
SearchProduct.prototype.getSuggestions = function(sKeyword){
	/* continue if sKeyword isn't null and the last pressed key wasn't up or 
    down */
	if(sKeyword != '' && !this._mIsKeyUpDownPressed)
	{
		// check to see if the sKeyword is in the cache
		isInCache = this.checkCache(sKeyword);
		// if sKeyword is in cache...
		if(isInCache == true)          
		{   
			// retrieve the results from the cache          
			this._mHttpRequestKeyword = sKeyword;
			this.mUserKeyword = sKeyword;     
			// display the results in the cache
			this.displayResults(sKeyword, this._mCache[sKeyword]);                          
		}
		// if the sKeyword isn't in cache, make an HTTP request
		else    
		{
			this.sendRequest(sKeyword);
		}
	}
}

/**
* Send the request to the server.
* @param string sUrlParams
*/
SearchProduct.prototype.sendRequest = function(sKeyword){
	/* if the XMLHttpRequest object isn't busy with a previous
	request... */
	if(this._mRequest.readyState == 4 || this._mRequest.readyState == 0) 
	{
		this._mHttpRequestKeyword = sKeyword;
		this.mUserKeyword = sKeyword;
		
		var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmd);
		str = Url.addUrlParam(str, 'search_str', sKeyword);
		str = Url.addUrlParam(str, 'type', 'xml');
		this._mRequest.open('GET', str, true);
		
		// Necessary for lexical closure, because of the onreadystatchange call.
		var oTemp = this
		this._mRequest.onreadystatechange = function(){
			oTemp.handleRequestStateChange();
		}
		
		this._mRequest.send(null);
	}
	// if the XMLHttpRequest object is busy...
	else
	{
		this.mUserKeyword = sKeyword;
		
		// clear any previous timeouts already set
		if(this._mTimeoutId != 0)
			clearTimeout(this._mTimeoutId);

		// try again in 0.5 seconds
		oTemp = this;
		this._mTimeoutId = setTimeout('oTemp.getSuggestions(oTemp.mUserKeyword);', 500);
	}
}

/**
* Method for displaying success.
* @param DocumentElement xmlDoc
*/
SearchProduct.prototype.displaySuccess = function(xmlDoc){
	// initialize the new array of products' data
	nameArray = new Array();
	// check to see if we have any results for the searched keyword
 
	if(xmlDoc.childNodes.length)
	{
		/* we retrieve the new products' data from the document element as 
       	an array */
		nameArray = this.xmlToArray(xmlDoc.getElementsByTagName('result'));       
	}
	// check to see if other keywords are already being searched for
	if(this._mUserKeyword != '' && (this._mHttpRequestKeyword == this.mUserKeyword))    
	{
		// display the results array
		this.displayResults(this._mHttpRequestKeyword, nameArray);
	}
	else
	{
		// add the results to the cache
		// we don't need to display the results since they are no longer useful
		this.addToCache(this._mHttpRequestKeyword, nameArray);              
	}
}

/**
 * Populates the list with the current suggestions.
 * @param string sKeyword
 * @param array resultsArray
 */
SearchProduct.prototype.displayResults = function(sKeyword, resultsArray){
	// start building the HTML table containing the results
	var div = '<table>'; 
	// if the searched for sKeyword is not in the cache then add it to the cache
	if(!this._mCache[sKeyword] && sKeyword)
		this.addToCache(sKeyword, resultsArray);
	// if the array of results is empty display a message
	if(resultsArray.length == 0)
	{
		div += '<tr><td>No hay resultados para <strong>' + sKeyword + '</strong></td></tr>'; 
		// and reset the counter for results
		this._mSuggestions = 0;
		this._mPosition = -1;
	}
	// display the results
	else
	{
		// resets the index of the currently selected suggestion
		this._mPosition = -1;
		// resets the flag indicating whether the up or down key has been pressed
		this._mIsKeyUpDownPressed = false;
		// get the number of results from the cache
		this._mSuggestions = this._mCache[sKeyword].length;
		// loop through all the results and generate the HTML list of results
		for (var i = 0; i < this._mCache[sKeyword].length; i++) 
		{  
			// retrieve the product id
			crtProductBarCode = this._mCache[sKeyword][i].bar_code;
			// retrieve the name of the product
			crtProductName = this._mCache[sKeyword][i].name;
			div += '<tr id="tr' + i + '" onclick="oSession.loadHref(\'index.php?cmd=get_product_by_bar_code&bar_code=' + crtProductBarCode +'\' );" ' + 
					'onmouseover="oSearchProduct.handleOnMouseOver(this);" onmouseout="oSearchProduct.handleOnMouseOut(this);">' +
					'<td id="' + i + '-' + crtProductName + '">';
			// check to see if the current product name length exceeds the maximum 
			// number of characters that can be displayed for a product name
			if(crtProductName.length <= this._mSuggestionMaxLength)
			{
				// bold the matching prefix of the product name and of the sKeyword
				div += '<b>' + crtProductName.substring(0, this._mHttpRequestKeyword.length) + '</b>';
				div += crtProductName.substring(this._mHttpRequestKeyword.length, crtProductName.length) + '</td>';
			}
			else
			{
				// check to see if the length of the current sKeyword exceeds 
				// the maximum number of characters that can be displayed
				if(this._mHttpRequestKeyword.length < this._mSuggestionMaxLength)
				{
					/* bold the matching prefix of the product name and that of the 
             		sKeyword */
					div += '<b>' + crtProductName.substring(0, this._mHttpRequestKeyword.length) + '</b>';
					div += crtProductName.substring(this._mHttpRequestKeyword.length, this._mSuggestionMaxLength) + 
							'</td>';
				}
				else
				{
					// bold the entire product name
					div += '<b>' + crtProductName.substring(0, this._mSuggestionMaxLength) + '</b></td>';          
				}
			}
			
			// retrieve the packaging of the product
			crtProductPackaging = this._mCache[sKeyword][i].packaging;
			div += '<td id="' + crtProductBarCode + '">' + crtProductPackaging + '</td></tr>';
		}
	}
	// end building the HTML table
	div += '</table>';
	var oSuggest = document.getElementById('suggest');
	// scroll to the top of the list
	this._mScrollDiv.scrollTop = 0;
	// update the suggestions list and make it visible
	oSuggest.innerHTML = div;
	this._mScrollDiv.style.visibility = 'visible';
	// if we had results we apply the type ahead for the current sKeyword
	if(resultsArray.length > 0)
		this.autocompleteKeyword();
}
 
/**
 * Transforms all the children of an xml node into an array.
 * @param Xml resultsXml
 */
SearchProduct.prototype.xmlToArray = function(resultsXml){
 	// initiate the resultsArray
 	var resultsArray = new Array();  
 	// loop through all the xml nodes retrieving the content
 	for(i = 0; i < resultsXml.length; i++){
 		resultsArray[i] = {};
 		resultsArray[i].bar_code = resultsXml.item(i).getElementsByTagName('bar_code')[0].firstChild.data;
 		resultsArray[i].name = resultsXml.item(i).getElementsByTagName('name')[0].firstChild.data;
 		resultsArray[i].packaging = resultsXml.item(i).getElementsByTagName('packaging')[0].firstChild.data;
 	}
 	// return the node's content as an array
 	return resultsArray;
}

/**
 * Method that autocompletes the typed keyword.
 */
SearchProduct.prototype.autocompleteKeyword = function(){
	// reset the position of the selected suggestion
	this._mPosition = 0;
	// deselect all suggestions
	this.deselectAll();
	// highlight the selected suggestion 
	document.getElementById('tr0').className = 'highlightrow';
	// update the keyword's value with the suggestion
	this.updateKeywordValue(document.getElementById('tr0'));  
	// apply the type-ahead style
	this.selectRange(this._mTxtWidget, this._mHttpRequestKeyword.length, this._mTxtWidget.value.length);  
	// set the autocompleted word to the keyword's value
	this._mAutocompletedKeyword = this._mTxtWidget.value;
}
 
/**
 *  Method that removes the style from all suggestions
 */
SearchProduct.prototype.deselectAll = function()
{ 
	for(i = 0; i < this._mSuggestions; i++)
	{
		var oCrtTr = document.getElementById('tr' + i);
		oCrtTr.className = '';    
	}
}

/**
 * Method that updates the keyword value with the value of the currently selected suggestion.
 * @param object oTr
 */
SearchProduct.prototype.updateKeywordValue = function(oTr){
	// retrieve the link for the current product
	var oElement = oTr.getElementsByTagName('TD')[0];
	var crtLink = oElement.id.substring(oElement.id.indexOf('-') + 1);
	// update the keyword's value
	this._mTxtWidget.value = unescape(crtLink);
}

/**
 * Method that selects a range in the text object passed as parameter.
 * @param object oText
 * @param integer start
 * @param integer length
 */
SearchProduct.prototype.selectRange = function(oText, start, length){
	// check to see if in IE or FF
	if (oText.createTextRange) 
	{
		//IE
		var oRange = oText.createTextRange(); 
		oRange.moveStart('character', start); 
		oRange.moveEnd('character', length - oText.value.length); 
		oRange.select();
	}
	else 
		// FF
		if (oText.setSelectionRange) 
		{
			oText.setSelectionRange(start, length);
		} 
	oText.focus();
}

/**
 * Method that handles the mouse entering over a suggestion's area event.
 * @param object oTr
 */
SearchProduct.prototype.handleOnMouseOver = function(oTr)
{
	this.deselectAll();
	oTr.className = 'highlightrow';  
	this._mPosition = oTr.id.substring(2, oTr.id.length);
}

/**
 * Method that handles the mouse exiting a suggestion's area event.
 * @param object oTr
 */
SearchProduct.prototype.handleOnMouseOut = function(oTr)
{
	oTr.className = '';   
	this._mPosition = -1;
}

/**
 * Method that handles the keys that are pressed.
 * @param object oEvent
 */
SearchProduct.prototype.handleKeyDown = function(oEvent){
	// get the event
	oEvent = (!oEvent) ? window.event : oEvent;
	// get the character code of the pressed button
	code = (oEvent.keyCode) ? oEvent.keyCode :
			((oEvent.which) ? oEvent.which : 0);
	
	// check to see if the event was keyup
	if (oEvent.type == 'keydown') 
	{    
		this._mIsKeyUpDownPressed = false; 
		/* if Enter is pressed we jump to the page of the current 
   		product */
		if(code == 13)
			// check to see if any product is currently selected
			if(this._mPosition >= 0)
			{
				var oTr = document.getElementById('tr' + this._mPosition);
				var crtLink = oTr.getElementsByTagName('TD')[1].id;
				oSession.loadHref('index.php?cmd=get_product_by_bar_code&bar_code=' + crtLink);
			}

		// if the down arrow is pressed we go to the next suggestion
		if(code == 40)
		{                   
			newTR=document.getElementById('tr' + (++this._mPosition));
			oldTR=document.getElementById('tr' + (--this._mPosition));
			// deselect the old selected suggestion   
			if(this._mPosition >= 0 && this._mPosition < this._mSuggestions - 1)
				oldTR.className = '';
 
			// select the new suggestion and update the keyword
			if(this._mPosition < this._mSuggestions - 1)
			{
				newTR.className = 'highlightrow';
				this.updateKeywordValue(newTR);
				this._mPosition++;
			}
			oEvent.cancelBubble = true;
			oEvent.returnValue = false;
			this._mIsKeyUpDownPressed = true;        
			// scroll down if the current window is no longer valid
			if(this._mPosition > this._mMaxVisiblePosition)
			{   
				this._mScrollDiv.scrollTop += 18;
				this._mMaxVisiblePosition += 1;
				this._mMinVisiblePosition += 1;
			}
		}

		// if the up arrow is pressed we go to the previous suggestion
		if(code == 38)
		{       
			newTR=document.getElementById('tr' + (--this._mPosition));
			oldTR=document.getElementById('tr' + (++this._mPosition));
			// deselect the old selected this._mPosition
			if(this._mPosition >= 0 && this._mPosition <= this._mSuggestions - 1)
			{       
				oldTR.className = '';
			}
			// select the new suggestion and update the keyword
			if(this._mPosition > 0)
			{
				newTR.className = 'highlightrow';
				this.updateKeywordValue(newTR);
				this._mPosition--;
				// scroll up if the current window is no longer valid
				if(this._mPosition < this._mMinVisiblePosition)
				{
					this._mScrollDiv.scrollTop -= 18;
					this._mMaxVisiblePosition -= 1;
					this._mMinVisiblePosition -= 1;
				}   
			}     
			else
				if(this._mPosition == 0)
					this._mPosition--;
			oEvent.cancelBubble = true;
			oEvent.returnValue = false;
			this._mIsKeyUpDownPressed = true;  
		}
		
		// If the tab key was pressed.
		if(code == 9)
			this.stopListening();
	}
}
 
/**
 * Handles click on all the div area.
 * @param Event oEvent
 */
SearchProduct.prototype.divHandleClick = function(oEvent){
 	oEvent = (!oEvent) ? window.event : oEvent;
 	oEvent.cancelBubble = true;
}
  
/**
 * If anything but the div area was click, stop listening.
 * @param Event oEvent
 */
SearchProduct.prototype.documentHandleClick = function(oEvent){
 	this.stopListening();
}