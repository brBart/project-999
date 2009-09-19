/**
 * Library with the SearchProductDetails base class.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 * @param Session oSession
 * @param SearchProduct oSearchProduct
 */
function SearchProductDetails(oSession, oSearchProduct){
	/**
	 * Holds the session tracker.
	 * @var Session
	 */
	this._mSession = oSession;
	
	/**
	 * Holds the object in charge for making the search queries to the server.
	 * @var SearchProduct
	 */
	this._mSearchProduct = oSearchProduct;
	
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
	 * Holds the setinterval id.
	 * @var integer
	 */
	this._mIntervalId = 0;
	
	/**
	 * Holds the last keyword for which suggests have been requested.
	 * @var string
	 */
	this._mUserKeyword = '';
	
	/**
	 * Number of suggestions received as results for the keyword.
	 * @var integer
	 */
	this._mTotalItems = 0;
	
	/**
	 * The currently selected suggestion (by arrow keys or mouse).
	 * @var integer
	 */
	this._mSelectedRow = 0;
	
	/**
	 * The maximum number of characters to be displayed for a suggestion.
	 * @var integer
	 */
	this._mSuggestionMaxLength = 30;
	
	/**
	 * The minimum position of the visible suggestions.
	 * @var integer
	 */
	this._mMinVisiblePosition = 1;
	
	/**
	 * The maximum position of the visible suggestions.
	 * @var integer
	 */
	this._mMaxVisiblePosition = 10;
	
	/**
	 * Holds the actual minimum position of the visible suggestions.
	 * @var integer
	 */
	this._mActualMinVisiblePosition = 0;
	
	/**
	 * Holds the actual maximum position of the visible suggestions.
	 * @var integer
	 */
	this._mActualMaxVisiblePosition = 0;
	
	/**
	 * Flag that indicates if the listening cycle has started.
	 * @var boolean
	 */
	this._mIsListening = false;
}

/**
* Sets the text input widget from where the user will input the search query.
* @param string sTxtWidget
*/
SearchProductDetails.prototype.init = function(sTxtWidget){
	this._mTxtWidget = document.getElementById(sTxtWidget);
	
	// Get the scroll div element.
	this._mScrollDiv = document.getElementById('scroll');
	this._mTxtWidget.setAttribute('autocomplete', 'off');
	
	oTemp = this;
	this._mTxtWidget.onkeydown = function(oEvent){
		oTemp.handleKeyDown(oEvent);
	}
	this._mTxtWidget.onfocus = function(){
		oTemp.startListening();
	}
	
	this._mSearchProduct.init(this);
}

/**
* Start the timeout cycle for the checkForChanges method.
*/
SearchProductDetails.prototype.startListening = function(){
	if(!this._mIsListening){
		oDiv = document.getElementById('search_product');
		
		oTemp = this;
		oDiv.onclick = function(oEvent){
			oTemp.divHandleClick(oEvent);
		}
		document.onclick = function(oEvent){
			oTemp.documentHandleClick(oEvent);
		}
		
		this._mIntervalId = setInterval('oTemp.checkForChanges()', 500);
		this._mIsListening = true;
	}
}

/**
* Clears the timeout cycle.
*/
SearchProductDetails.prototype.stopListening = function(){
	oDiv = document.getElementById('search_product');
	 	
	oDiv.onclick = null;
	document.onclick = null;
	 
	clearInterval(this._mIntervalId);
	this.hideSuggestions();
	this._mIsListening = false;
}

/**
* Hide the div containing the suggestions.
*/
SearchProductDetails.prototype.hideSuggestions = function(){
	this._mScrollDiv.style.visibility = 'hidden';
}

/**
* Check if the user has change the text in the input text widget.
*/
SearchProductDetails.prototype.checkForChanges  = function(){
	// retrieve the keyword object 
	var keyword = this._mTxtWidget.value;
	// check to see if the keyword is empty
	if(keyword == '')
	{
		// hide the suggestions
		this.hideSuggestions();
		// reset the keyword 
		this._mUserKeyword = '';
		this._mSelectedRow = 0;
	}
	// check to see if there are any changes
	else if(this._mUserKeyword != keyword){
		this._mUserKeyword = keyword;
		// update the suggestions
		this._mSearchProduct.getSuggestions(keyword);
	}
}

/**
* Populates the list with the current suggestions.
* @param string sKeyword
* @param array resultsArray
*/
SearchProductDetails.prototype.displayResults = function(sKeyword, resultsArray){
	if(this._mUserKeyword == sKeyword){
		// start building the HTML table containing the results
		var div = '<table onmouseout="oSearchDetails.handleOnMouseOut();">'; 
		// if the array of results is empty display a message
		if(resultsArray.length == 0)
		{
			div += '<tr><td>No hay resultados para <strong>' + sKeyword + '</strong></td></tr>'; 
			// and reset the counter for results
			this._mTotalItems = 0;
			this._mSelectedRow = 0;
		}
		// display the results
		else
		{
			this._mSelectedRow = 0;
			// Get the number of results from the cache.
			this._mTotalItems = resultsArray.length;
			// loop through all the results and generate the HTML list of results
			for (var i = 0; i < resultsArray.length; i++) 
			{  
				// retrieve the product id
				crtProductBarCode = resultsArray[i]['bar_code'];
				// retrieve the name of the product
				crtProductName = resultsArray[i]['name'];
				div += '<tr id="tr' + (i + 1) + '" onclick="oSession.loadHref(\'index.php?cmd=get_product_by_bar_code&bar_code=' + crtProductBarCode +'\' );" ' + 
						'onmouseover="oSearchDetails.handleOnMouseOver(this);">' + '<td id="' + (i + 1) + '-' + crtProductName + '">';
				// check to see if the current product name length exceeds the maximum 
				// number of characters that can be displayed for a product name
				if(crtProductName.length < this._mSuggestionMaxLength)
				{
					// bold the matching prefix of the product name and of the sKeyword
					div += '<b>' + crtProductName.substring(0, sKeyword.length) + '</b>' +
							crtProductName.substring(sKeyword.length, crtProductName.length) + '</td>';
				}
				else
				{
					// check to see if the length of the current sKeyword exceeds 
					// the maximum number of characters that can be displayed
					if(sKeyword.length < this._mSuggestionMaxLength)
					{
						/* bold the matching prefix of the product name and that of the 
	            		sKeyword */
						div += '<b>' + crtProductName.substring(0, sKeyword.length) + '</b>' +
								crtProductName.substring(sKeyword.length, this._mSuggestionMaxLength) + 
								'</td>';
					}
					else
					{
						// bold the entire product name
						div += '<b>' + crtProductName.substring(0, this._mSuggestionMaxLength) + '</b></td>';          
					}
				}
				
				// retrieve the packaging of the product
				div += '<td id="' + crtProductBarCode + '">' + resultsArray[i]['packaging'] + '</td></tr>';
			}
		}
		// end building the HTML table
		div += '</table>';
		// scroll to the top of the list
		this._mScrollDiv.scrollTop = 0;
		// update the suggestions list and make it visible
		this._mScrollDiv.innerHTML = div;
		this._mScrollDiv.style.visibility = 'visible';
		// if we had results we apply the type ahead for the current sKeyword
		if(resultsArray.length > 0){
			this.moveFirst();
			this.autocompleteKeyword(sKeyword.length, this._mTxtWidget.value.length);
		}
	}
}

/**
* Selects the first row.
*/
SearchProductDetails.prototype.moveFirst = function(){
	if(this._mTotalItems > 0){
		this.selectRow(1);
		this.updateKeywordValue(this._mSelectedRow);
		this.updateScroll();
	}
}

/**
* Selects the previous row.
*/
SearchProductDetails.prototype.movePrevious = function(){
	if(this._mTotalItems > 0 && this._mSelectedRow > 1){
		this.selectRow(this._mSelectedRow - 1);
		this.updateKeywordValue(this._mSelectedRow);
		this.updateScroll();
	}
	else if(this._mTotalItems > 0 && this._mSelectedRow == 1){
		oTr = document.getElementById('tr' + this._mSelectedRow);
		oTr.className = '';
		this._mSelectedRow = 0;
	}
}

/**
* Selects the next row.
*/
SearchProductDetails.prototype.moveNext = function(){
	if(this._mTotalItems > 0 && this._mSelectedRow < this._mTotalItems){
		this.selectRow(this._mSelectedRow + 1);
		this.updateKeywordValue(this._mSelectedRow);
		this.updateScroll();
	}
}

/**
* Select the row in the iPos position.
* @param integer iPos
*/
SearchProductDetails.prototype.selectRow = function(iPos){
	newTr = document.getElementById('tr' + iPos);
	newTr.className = 'highlightrow';
	
	if(this._mSelectedRow > 0 && this._mSelectedRow != iPos){
		oldTr = document.getElementById('tr' + this._mSelectedRow);
		oldTr.className = '';
	}
	
	this._mSelectedRow = parseInt(iPos);
}

/**
* Method that selects a range in the text object passed as parameter.
* @param integer start
* @param integer length
*/
SearchProductDetails.prototype.autocompleteKeyword = function(start, length){
	// check to see if in IE or FF
	if (this._mTxtWidget.createTextRange)
	{
		//IE
		var oRange = this._mTxtWidget.createTextRange(); 
		oRange.moveStart('character', start); 
		oRange.moveEnd('character', length - this._mTxtWidget.value.length); 
		oRange.select();
	}
	else 
		// FF
		if (this._mTxtWidget.setSelectionRange) 
		{
			this._mTxtWidget.setSelectionRange(start, length);
		}
}

/**
* Method that updates the keyword value with the value of the currently selected suggestion.
* @param integer iPos
*/
SearchProductDetails.prototype.updateKeywordValue = function(iPos){
	var oTr = document.getElementById('tr' + iPos);
	// retrieve the link for the current product
	var oTd = oTr.getElementsByTagName('TD')[0];
	var crtLink = oTd.id.substring(oTd.id.indexOf('-') + 1);
	// update the keyword's value
	this._mTxtWidget.value = unescape(crtLink);
	this._mUserKeyword = this._mTxtWidget.value;
}

/**
* Method that handles the keys that are pressed.
* @param object oEvent
*/
SearchProductDetails.prototype.handleKeyDown = function(oEvent){
	// get the event
	oEvent = (!oEvent) ? window.event : oEvent;
	// get the character code of the pressed button
	code = (oEvent.keyCode) ? oEvent.keyCode :
			((oEvent.which) ? oEvent.which : 0);
	
	// check to see if the event was keyup
	if (oEvent.type == 'keydown') 
	{     
		/* if Enter is pressed we jump to the page of the current 
  		product */
		if(code == 13)
			// check to see if any product is currently selected
			if(this._mSelectedRow > 0)
			{
				var oTr = document.getElementById('tr' + this._mSelectedRow);
				var crtBarCode = oTr.getElementsByTagName('TD')[1].id;
				this.doAction(crtBarCode);
			}
  		
  		// if the up arrow is pressed we go to the previous suggestion
		if(code == 38)
			this.movePrevious();

		// if the down arrow is pressed we go to the next suggestion
		if(code == 40)
			this.moveNext();
		
		// If the tab key was pressed.
		if(code == 9)
			this.stopListening();
	}
}

/**
* Method that handles the mouse entering over a suggestion's area event.
* @param object oTr
*/
SearchProductDetails.prototype.handleOnMouseOver = function(oTr)
{
	this.selectRow(oTr.id.substring(2));
}

/**
* Method that handles the mouse exiting a suggestion's area event.
* @param object oTr
*/
SearchProductDetails.prototype.handleOnMouseOut = function()
{
	var oTr = document.getElementById('tr' + this._mSelectedRow);
	oTr.className = '';   
	this._mSelectedRow = 0;
}

/**
* Handles click on all the div area.
* @param Event oEvent
*/
SearchProductDetails.prototype.divHandleClick = function(oEvent){
	oEvent = (!oEvent) ? window.event : oEvent;
	oEvent.cancelBubble = true;
}
 
/**
* If anything but the div area was click, stop listening.
* @param Event oEvent
*/
SearchProductDetails.prototype.documentHandleClick = function(oEvent){
	this.stopListening();
}

/**
 * Abstract method.
 * @param string sBarCode
 */
SearchProductDetails.prototype.doAction = function(sBarCode){
	return 0;
}
 
/**
 * Move the scroll according to the selected row.
 */
SearchProductDetails.prototype.updateScroll = function(){
	if(this._mSelectedRow == 1){
		this._mScrollDiv.scrollTop = 0;
		this._mActualMinVisiblePosition = this._mMinVisiblePosition;
		this._mActualMaxVisiblePosition = this._mMaxVisiblePosition;
	}
	// scroll up if the current window is no longer valid
	else if(this._mSelectedRow < this._mActualMinVisiblePosition)
	{
		this._mScrollDiv.scrollTop -= 18;
		this._mActualMaxVisiblePosition -= 1;
		this._mActualMinVisiblePosition -= 1;
	}
	// scroll down if the current window is no longer valid
	else if(this._mSelectedRow > this._mActualMaxVisiblePosition)
	{   
		this._mScrollDiv.scrollTop += 18;
		this._mActualMaxVisiblePosition += 1;
		this._mActualMinVisiblePosition += 1;
	}
}