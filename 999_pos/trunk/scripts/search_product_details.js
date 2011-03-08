/**
 * @fileOverview Library with the SearchProductDetails base class.
 * @author Roberto Oliveros
 */

/**
 * @class Manages how to display the details that results from a product search.
 * @constructor
 * @param {Session} oSession
 * @param {SearchProduct} oSearchProduct
 * @param {EventDelegator} oEventDelegator
 */
function SearchProductDetails(oSession, oSearchProduct, oEventDelegator){
	/**
	 * Flag that indicates if the div element was clicked.
	 * @type Boolean
	 */
	this.mWasClicked = false;
	
	/**
	 * Holds the session tracker.
	 * @type Session
	 */
	this._mSession = oSession;
	
	/**
	 * Holds the object in charge for making the search queries to the server.
	 * @type SearchProduct
	 */
	this._mSearchProduct = oSearchProduct;
	
	/**
	 * Holds the object in charge of handling the click events.
	 * @type EventDelegator
	 */
	this._mEventDelegator = oEventDelegator;
	
	/**
	 * Holds the text input element.
	 * @type HtmlElement
	 */
	this._mTxtWidget = null;
	
	/**
	 * Holds the name of the variable which holds the instance of this class on the html document.
	 * @type String
	 */
	this._mDetailsObj = '';
	
	/**
	 * Holds the div element containing the suggest table.
	 * @type HtmlElement
	 */
	this._mScrollDiv = null;
	
	/**
	 * Holds the setinterval id.
	 * @type Integer
	 */
	this._mIntervalId = 0;
	
	/**
	 * Holds the last keyword for which suggests have been requested.
	 * @type String
	 */
	this._mUserKeyword = '';
	
	/**
	 * Number of suggestions received as results for the keyword.
	 * @type Integer
	 */
	this._mTotalItems = 0;
	
	/**
	 * The currently selected suggestion.
	 * @type Integer
	 */
	this._mSelectedRow = 0;
	
	/**
	 * The maximum number of characters to be displayed for a suggestion.
	 * @type Integer
	 */
	this._mSuggestionMaxLength = 30;
	
	/**
	 * The minimum position of the visible suggestions.
	 * @type Integer
	 */
	this._mMinVisiblePosition = 1;
	
	/**
	 * The maximum position of the visible suggestions.
	 * @type Integer
	 */
	this._mMaxVisiblePosition = 10;
	
	/**
	 * Holds the actual minimum position of the visible suggestions.
	 * @type Integer
	 */
	this._mActualMinVisiblePosition = 0;
	
	/**
	 * Holds the actual maximum position of the visible suggestions.
	 * @type Integer
	 */
	this._mActualMaxVisiblePosition = 0;
	
	/**
	 * Flag that indicates if the listening cycle has started.
	 * @type Boolean
	 */
	this._mIsListening = false;
}

/**
 * Sets the text input element where the user will enter the search query.
 * @param {String} sTxtWidget The id of the input element.
 * @param {String} sDetailsObj The name of the variable which holds the instance of this class.
 * @param {Boolean} bIncludeDeactivated Include deactivated products.
 */
SearchProductDetails.prototype.init = function(sTxtWidget, sDetailsObj, bIncludeDeactivated){
	// Register with the event delegator.
	this._mEventDelegator.registerObject(this);
	
	this._mTxtWidget = document.getElementById(sTxtWidget);
	this._mDetailsObj = sDetailsObj;
	
	// Get the scroll div element.
	this._mScrollDiv = document.getElementById('scroll');
	this._mTxtWidget.setAttribute('autocomplete', 'off');
	
	var oTemp = this;
	this._mTxtWidget.onkeydown = function(oEvent){
		oTemp.handleKeyDown(oEvent);
	}
	this._mTxtWidget.onfocus = function(){
		oTemp.startListening();
	}
	
	this._mSearchProduct.init(this, bIncludeDeactivated);
}

/**
 * Start the timeout cycle for the checkForChanges method.
 */
SearchProductDetails.prototype.startListening = function(){
	if(!this._mIsListening){
		oDiv = document.getElementById('search_product');
		
		var oTemp = this;
		oDiv.onclick = function(oEvent){
			oTemp.divHandleClick(oEvent);
		}
		
		oTemp1 = this;
		this._mIntervalId = setInterval('oTemp1.checkForChanges()', 500);
		this._mIsListening = true;
	}
}

/**
 * Clears the timeout cycle.
 */
SearchProductDetails.prototype.stopListening = function(){
	oDiv = document.getElementById('search_product');
	oDiv.onclick = null;
	 
	clearInterval(this._mIntervalId);
	this.hideSuggestions();
	this._mIsListening = false;
}

/**
 * Hides the div element containing the suggestions.
 */
SearchProductDetails.prototype.hideSuggestions = function(){
	this._mScrollDiv.innerHTML = '';
	this._mScrollDiv.style.visibility = 'hidden';
}

/**
 * Check if the user has change the text in the input element.
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
 * Populates the div element with the resulting suggestions.
 * @param {String} sKeyword The actual search keyword.
 * @param {Array} resultsArray
 */
SearchProductDetails.prototype.displayResults = function(sKeyword, resultsArray){
	if(this._mUserKeyword == sKeyword){
		// start building the HTML table containing the results
		var div = '<table onmouseout="' + this._mDetailsObj + '.handleOnMouseOut();">'; 
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
				div += '<tr id="tr' + (i + 1) + '" onclick="'+ this._mDetailsObj + '.doAction(this);" ' + 
						'onmouseover="' + this._mDetailsObj + '.handleOnMouseOver(this);">' + '<td id="' + (i + 1) + '-' + crtProductName.htmlEntities() + '">';
				// check to see if the current product name length exceeds the maximum 
				// number of characters that can be displayed for a product name
				if(crtProductName.length < this._mSuggestionMaxLength)
				{
					// bold the matching prefix of the product name and of the sKeyword
					div += '<b>' + crtProductName.substring(0, sKeyword.length).htmlEntities() + '</b>' +
							crtProductName.substring(sKeyword.length, crtProductName.length).htmlEntities() + '</td>';
				}
				else
				{
					// check to see if the length of the current sKeyword exceeds 
					// the maximum number of characters that can be displayed
					if(sKeyword.length < this._mSuggestionMaxLength)
					{
						/* bold the matching prefix of the product name and that of the 
	            		sKeyword */
						div += '<b>' + crtProductName.substring(0, sKeyword.length).htmlEntities() + '</b>' +
								crtProductName.substring(sKeyword.length, this._mSuggestionMaxLength).htmlEntities() + 
								'</td>';
					}
					else
					{
						// bold the entire product name
						div += '<b>' + crtProductName.substring(0, this._mSuggestionMaxLength).htmlEntities() + '</b></td>';          
					}
				}
				
				// retrieve the packaging of the product
				div += '<td id="' + crtProductBarCode.htmlEntities() + '">' + resultsArray[i]['packaging'].htmlEntities() + '</td></tr>';
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
			TextRange.selectRange(this._mTxtWidget, sKeyword.length, this._mTxtWidget.value.length);
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
 * @param {Integer} iPos
 */
SearchProductDetails.prototype.selectRow = function(iPos){
	var newTr = document.getElementById('tr' + iPos);
	newTr.className = 'highlightrow';
	
	if(this._mSelectedRow > 0 && this._mSelectedRow != iPos){
		var oldTr = document.getElementById('tr' + this._mSelectedRow);
		oldTr.className = '';
	}
	
	this._mSelectedRow = parseInt(iPos);
}

/**
 * Method that updates the keyword value in the input element with the value of the desired suggestion.
 * @param {Integer} iPos The row position.
 */
SearchProductDetails.prototype.updateKeywordValue = function(iPos){
	var oTr = document.getElementById('tr' + iPos);
	// retrieve the name for the current product
	var oTd = oTr.getElementsByTagName('TD')[0];
	var crtName = oTd.id.substring(oTd.id.indexOf('-') + 1);
	// update the keyword's value
	this._mTxtWidget.value = unescape(crtName);
	this._mUserKeyword = this._mTxtWidget.value;
}

/**
 * Method that handles the keys that are pressed.
 * @param {Event} oEvent
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
		/* if Enter is pressed we take the respective action. */
		if(code == 13)
			// check to see if any product is currently selected
			if(this._mSelectedRow > 0)
			{
				var oTr = document.getElementById('tr' + this._mSelectedRow);
				this.doAction(oTr);
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
 * Method that handles the mouse entering over a suggestion's area.
 * @param {HtmlElement} oTr
 */
SearchProductDetails.prototype.handleOnMouseOver = function(oTr)
{
	this.selectRow(oTr.id.substring(2));
}

/**
 * Method that handles the mouse exiting the div element area.
 */
SearchProductDetails.prototype.handleOnMouseOut = function()
{
	var oTr = document.getElementById('tr' + this._mSelectedRow);
	// If there are results.
	if(oTr){
		oTr.className = '';   
		this._mSelectedRow = 0;
	}
}

/**
 * Handles click on all the div element area.
 * @param {Event} oEvent
 */
SearchProductDetails.prototype.divHandleClick = function(oEvent){
	this.mWasClicked = true;
}
 
/**
 * If anything but the div element area was click, stop listening.
 */
SearchProductDetails.prototype.blur = function(){
	if(this._mIsListening)
		this.stopListening();
}

/**
 * Abstract method.
 * @param {HtmlElement} oTr
 */
SearchProductDetails.prototype.doAction = function(oTr){
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