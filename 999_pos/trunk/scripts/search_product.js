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
function SearchProductCommand(oSession, oConsole, oRequest){
	// Call the parent constructor.
	Command.call(this, oSession, oConsole, oRequest);
	
	/**
	 * Holds the text input.
	 * @var object
	 */
	this._mTxtWidget = null;
	
	/**
	 * Holds the div containing the suggest table.
	 * @var object
	 */
	this._mSuggestDiv = null;
	
	/**
	 * Holds the settimeout id.
	 * @var integer
	 */
	this._mTimeoutId = 0;
	
	/**
	 * Holds the last keyword for which suggests have been requested.
	 * @var string
	 */
	this._mUserKeyword = '';
	
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
	 * Flag that indicates if there are results for the current requested keyword.
	 * @var boolean
	 */
	this._mHasResults = false;
	
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
}

/**
* Inherit the Command class methods.
*/
SearchProductCommand.prototype = new Command();

/**
 * Sets the text input widget from where the user will input the search query.
 * @param object oTxtWidget
 */
SearchProductCommand.prototype.init = function(oTxtWidget){
	oTemp = this;
	oTxtWidget.onkeydown = function(oEvent){
		oTemp.handleKeyDown(oEvent);
	}
	oTxtWidget.onfocus = function(){
		oTemp.startListening();
	}
	oTxtWidget.onblur = function(){
		oTemp.stopListening();
	}
	
	this._mTxtWidget = oTxtWidget;
	this._mSuggestDiv = oTxtWidget.nextSibling;
	// Initiate the cache.
	this._mCache = new Object();
}

/**
 * Start the timeout cycle for the checkForChanges method.
 */
SearchProductCommand.prototype.startListening = function(){
	oTemp = this;
	this._mTimeoutId = setTimeout('oTemp.checkForChanges()', 500);
}
 
/**
 * Clears the timeout cycle.
 */
SearchProductCommand.prototype.stopListening = function(){
	clearTimeout(this._mTimeoutId);
}

/**
 * Check if the user has change the text in the input text widget.
 */
SearchProductCommand.prototype.checkForChanges  = function(){
	// retrieve the keyword object 
	var keyword = this._mTxtWidget.value;
	// check to see if the keyword is empty
	if(keyword == "")
	{
	  // hide the suggestions
	  this.hideSuggestions();
	  // reset the keywords 
	  this._mUserKeyword="";
	  this._mHttpRequestKeyword="";
	}
	// set the timer for a new check 
	oTemp = this;
	this._mTimeoutId = setTimeout('oTemp.checkForChanges()', 500);
	// check to see if there are any changes
	if((this._mUserKeyword != keyword) && 
	   (this._mAutocompletedKeyword != keyword) && 
	   (!this._mIsKeyUpDownPressed))
	  // update the suggestions
	  this.getSuggestions(keyword);
}

/**
 * Hide the div containing the suggestions.
 */
SearchProductCommand.prototype.hideSuggestions = function(){
	this._mSuggestDiv.visibility = 'hidden';
}

/**
 * Returns a list of results from the keyword suggestions.
 * @param string sKeyword
 */
SearchProductCommand.prototype.getSuggestions = function(sKeyword){
	/* continue if sKeyword isn't null and the last pressed key wasn't up or 
    down */
	if(sKeyword != "" && !this._mIsKeyUpDownPressed)
	{
		// check to see if the sKeyword is in the cache
		isInCache = this.checkCache(sKeyword);
		// if sKeyword is in cache...
		if(isInCache == true)          
		{   
			// retrieve the results from the cache          
			this._mHttpRequestKeyword=sKeyword;
			this._mUserKeyword=sKeyword;     
			// display the results in the cache
			this.displayResults(sKeyword, this._mCache[sKeyword]);                          
		}
		// if the sKeyword isn't in cache, make an HTTP request
		else    
		{
			this._mHttpRequestKeyword = sKeyword;
			this._mUserKeyword = sKeyword;
			var str = Url.addUrlParam(Url.getUrl(), 'cmd', this._mCmd);
			str = Url.addUrlParam(str, 'search_str', sKeyword);
			this.sendRequest(str);
		}
	}
}

/**
* Send the request to the server.
* @param string sUrlParams
*/
SearchProductCommand.prototype.sendRequest = function(sUrlParams){
	/* if the XMLHttpRequest object isn't busy with a previous
	request... */
	if(this._mRequest.readyState == 4 || this._mRequest.readyState == 0) 
	{    
		sUrlParams = Url.addUrlParam(sUrlParams, 'type', 'xml');
		this._mRequest.open('GET', sUrlParams, true);
		
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
		// retain the sKeyword the user wanted             
		this._mUserKeyword = sKeyword;
		// clear any previous timeouts already set
		if(this._mTimeoutId != 0){
			clearTimeout(this._mTimeoutId);
			this._mTimeoutId = 0;
		}
		// try again in 0.5 seconds
		oTemp = this;
		this._mTimeoutId = setTimeout('oTemp.getSuggestions(' + this._mUserKeyword + ');', 500);
	}
}

/**
 * Method that checks to see if the keyword specified as parameter is in the cache or tries to find the
 * longest matching prefixes in the cache and adds them in the cache for the current keyword parameter.
 * @param string sKeyword
 */
SearchProductCommand.prototype.checkCache = function(sKeyword){
	// check to see if the sKeyword is already in the cache
	if(this._mCache[sKeyword])
		return true;
	// try to find the biggest prefixes
	for(i=sKeyword.length-2; i>=0; i--)
	{
		// compute the current prefix sKeyword 
		var currentKeyword = sKeyword.substring(0, i+1);
		// check to see if we have the current prefix sKeyword in the cache
		if(this._mCache[currentKeyword])
		{            
			// the current keyword's results already in the cache
			var cacheResults = this._mCache[currentKeyword];
			// the results matching the sKeyword in the current cache results
			var keywordResults = new Array();
			var keywordResultsSize = 0;            
			// try to find all matching results starting with the current prefix
			for(j=0;j<cacheResults.length;j++)
			{
				if(cacheResults[j].indexOf(sKeyword) == 0)               
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
SearchProductCommand.prototype.addToCache = function(sKeyword, values){
	// create a new array entry in the cache
	this._mCache[sKeyword] = new Array();
	// add all the values to the sKeyword's entry in the cache
	for(i=0; i<values.length; i++)
		this._mCache[sKeyword][i] = values[i];
}

/**
 * Populates the list with the current suggestions.
 * @param string sKeyword
 * @param array resultsArray
 */
SearchProductCommand.prototype.displayResults = function(sKeyword, resultsArray){
	// start building the HTML table containing the results  
	var div = "<table>"; 
	// if the searched for sKeyword is not in the cache then add it to the cache
	if(!this._mCache[sKeyword] && sKeyword)
		this.addToCache(sKeyword, resultsArray);
	// if the array of results is empty display a message
	if(resultsArray.length == 0)
	{
		div += "<tr><td>No results found for <strong>" + sKeyword + 
        		"</strong></td></tr>";
		// set the flag indicating that no results have been found 
		// and reset the counter for results
		this._mHasResults = false;
		this._mSuggestions = 0;
	}
	// display the results
	else
	{
		// resets the index of the currently selected suggestion
		this._mPosition = -1;
		// resets the flag indicating whether the up or down key has been pressed
		this._mIsKeyUpDownPressed = false;
		/* sets the flag indicating that there are results for the searched 
       	for sKeyword */
		this._mHasResults = true;
		// get the number of results from the cache
		this._mSuggestions = this._mCache[sKeyword].length;
		// loop through all the results and generate the HTML list of results
		for (var i=0; i<this._mCache[sKeyword].length; i++) 
		{  
			// retrieve the current function
			crtFunction = this._mCache[sKeyword][i];
			// set the string link for the for the current function 
			// to the name of the function
			crtFunctionLink = crtFunction;
			// replace the _ with - in the string link
			while(crtFunctionLink.indexOf("_") !=-1)
				crtFunctionLink = crtFunctionLink.replace("_","-");
			// start building the HTML row that contains the link to the 
			// PHP help page of the current function
 
			div += "<tr id='tr" + i + 
            		"' onclick='location.href=document.getElementById(\"a" + i + 
            		"\").href;' onmouseover='handleOnMouseOver(this);' " + 
            		"onmouseout='handleOnMouseOut(this);'>" + 
            		"<td align='left'><a id='a" + i + 
            		"' href='" + phpHelpUrl + crtFunctionLink + ".php";
			// check to see if the current function name length exceeds the maximum 
			// number of characters that can be displayed for a function name
			if(crtFunction.length <= this_mSuggestionMaxLength)
			{
				// bold the matching prefix of the function name and of the sKeyword
				div += "'><b>" + crtFunction.substring(0, this._mHttpRequestKeyword.length) + "</b>"
						div += crtFunction.substring(this._mHttpRequestKeyword.length, crtFunction.length) + 
                         "</a></td></tr>";
			}
			else
			{
				// check to see if the length of the current sKeyword exceeds 
				// the maximum number of characters that can be displayed
				if(this._mHttpRequestKeyword.length < this_mSuggestionMaxLength)
				{
					/* bold the matching prefix of the function name and that of the 
             		sKeyword */
					div += "'><b>" + crtFunction.substring(0, this._mHttpRequestKeyword.length) + "</b>"
							div += crtFunction.substring(this._mHttpRequestKeyword.length, this_mSuggestionMaxLength) + 
							"</a></td></tr>";
				}
				else
				{
					// bold the entire function name
					div += "'><b>" + crtFunction.substring(0,this_mSuggestionMaxLength) + "</b></td></tr>";          
				}
			}
		}
	}
	// end building the HTML table
	div += "</table>";
	var oScroll = document.getElementById("scroll");
	// scroll to the top of the list
	oScroll.scrollTop = 0;
	// update the suggestions list and make it visible
	this._mSuggestDiv.innerHTML = div;
	oScroll.style.visibility = "visible";
	// if we had results we apply the type ahead for the current sKeyword
	if(resultsArray.length > 0)
		this.autocompleteKeyword();
}

/**
 * Method that autocompletes the typed keyword.
 */
SearchProductCommand.prototype.autocompleteKeyword = function(){
	// reset the position of the selected suggestion
	this._mPosition=0;
	// deselect all suggestions
	this.deselectAll();
	// highlight the selected suggestion 
	document.getElementById("tr0").className="highlightrow";  
	// update the keyword's value with the suggestion
	this.updateKeywordValue(document.getElementById("tr0"));  
	// apply the type-ahead style
	this.selectRange(this._mTxtWidget,this._mHttpRequestKeyword.length,this._mTxtWidget.value.length);  
	// set the autocompleted word to the keyword's value
	this._mAutocompletedKeyword=this._mTxtWidget.value;
}

/**
 * Method that removes the style from all suggestions.
 */
SearchProductCommand.prototype.selectAll = function(){
	for(i=0; i<this._mSuggestions; i++)
	{
		var oCrtTr = document.getElementById("tr" + i);
	    oCrtTr.className = "";    
	}
}

/**
 * Method that updates the keyword value with the value of the currently selected suggestion.
 * @param object oTr
 */
SearchProductCommand.prototype.updateKeywordValue = function(oTr){
	// retrieve the link for the current function
	var crtLink = document.getElementById("a" + oTr.id.substring(2,oTr.id.length)).toString();
	// replace - with _ and leave out the .php extension
 
	crtLink = crtLink.replace("-", "_");
	crtLink = crtLink.substring(0, crtLink.length - 4);
	// update the keyword's value
	this._mTxtWidget.value = unescape(crtLink.substring(phpHelpUrl.length, crtLink.length));
}

/**
 * Method that selects a range in the text object passed as parameter.
 * @param object oText
 * @param integer start
 * @param integer length
 */
SearchProductCommand.prototype.selectRange = function(oText, start, length){
	// check to see if in IE or FF
	if (oText.createTextRange) 
	{
		//IE
		var oRange = oText.createTextRange(); 
		oRange.moveStart("character", start); 
		oRange.moveEnd("character", length - oText.value.length); 
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
SearchProductCommand.prototype.handleOnMouseOver = function(oTr)
{
	this.deselectAll();
	oTr.className = "highlightrow";  
	this._mPosition = oTr.id.substring(2, oTr.id.length);
}

/**
 * Method that handles the mouse exiting a suggestion's area event.
 * @param object oTr
 */
SearchProductCommand.prototype.handleOnMouseOut = function(oTr)
{
	oTr.className = "";   
	this._mPosition = -1;
}

/**
* Method for displaying success.
* @param DocumentElement xmlDoc
*/
SearchProductCommand.prototype.displaySuccess = function(xmlDoc){
	// initialize the new array of functions' names
	nameArray = new Array();
	// check to see if we have any results for the searched keyword
 
	if(xmlDoc.childNodes.length)
	{
		/* we retrieve the new functions' names from the document element as 
       	an array */
		nameArray= this.xmlToArray(xmlDoc.getElementsByTagName("name"));       
	}
	// check to see if other keywords are already being searched for
	if(this._mHttpRequestKeyword == this._mUserKeyword)    
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


SearchProductCommand.prototype.xmlToArray = function(){
	// initiate the resultsArray
	  var resultsArray= new Array();  
	  // loop through all the xml nodes retrieving the content  
	  for(i=0;i<resultsXml.length;i++)
	    resultsArray[i]=resultsXml.item(i).firstChild.data;
	  // return the node's content as an array
	  return resultsArray;
}