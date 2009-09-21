/**
 * Library with the object page class.
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
 * @param EventDelegator oEventDelegator
 */
function ObjectPage(oSession, oConsole, oRequest, sKey, oMachine, oEventDelegator){
	// Call the parent constructor.
	Details.call(this, oSession, oConsole, oRequest, sKey, oMachine, oEventDelegator);
	
	/**
	 * Holds the total number of pages.
	 * @var integer
	 */
	this._mTotalPages = 0;
}

/**
* Inherit the Details class methods.
*/
ObjectPage.prototype = new Details();

/**
* Method for displaying success.
* @param DocumentElement xmlDoc
*/
ObjectPage.prototype.displaySuccess = function(xmlDoc){
	// Call parent method first.
	Details.prototype.displaySuccess.call(this, xmlDoc);
	// Obtain the total number of pages.
	this._mTotalPages = xmlDoc.getElementsByTagName('total_pages')[0].firstChild.data;
}

/**
 * Sends the request to the server for fetching the last page.
 */
ObjectPage.prototype.getLastPage = function(){
	var str = Url.addUrlParam(Url.getUrl(), 'cmd', this.getLastPageCmd());
	str = Url.addUrlParam(str, 'key', this._mKey);
	this.sendRequest(str);
}

/**
* Sends the request to the server for fetching the desired page.
* @param integer iPage
*/
ObjectPage.prototype.getPage = function(iPage){
	if(iPage < 0)
		this._mConsole.displayError('Interno: Argumento iPage inv&aacute;lido.');
	else{
		var str = Url.addUrlParam(Url.getUrl(), 'cmd', this.getPageCmd());
		str = Url.addUrlParam(str, 'page', iPage);
		str = Url.addUrlParam(str, 'key', this._mKey);
		this.sendRequest(str);
	}
}

/**
 * Updates the actual page.
 */
ObjectPage.prototype.updatePage = function(){
	this.getPage(this._mPage);
}
 
/** 
 * Move to the previous page and set focus on the last row.
 */
ObjectPage.prototype.moveToPreviousPage = function(){
	this.getPage(this._mPage - 1);
	this.setFocus();
	this.moveLast();
}

/** 
* Move to the next page and set focus on the first row.
*/
ObjectPage.prototype.moveToNextPage = function(){
	this.getPage(this._mPage + 1);
	this.setFocus();
	this.moveFirst();
}
 
/**
  * Selects the previous row.
  */
ObjectPage.prototype.movePrevious = function(){
 	if(this._mPageItems > 0 && this._mSelectedRow > 1)
 		this.selectRow(this._mSelectedRow - 1);
 	else if(this._mPage > 1)
 		this.moveToPreviousPage();
}

/**
  * Selects the next row.
  */
ObjectPage.prototype.moveNext = function(){
 	if(this._mPageItems > 0 && this._mSelectedRow < this._mPageItems)
 		this.selectRow(this._mSelectedRow + 1);
 	else if(this._mTotalPages > 0 && this._mPage < this._mTotalPages)
 		this.moveToNextPage();
}
 

/**
 * Returns the name of the last page command on the server.
 * @return string
 */
ObjectPage.prototype.getLastPageCmd = function(){
	return 0;
}

/**
* Returns the name of the page command on the server.
* @return string
*/
ObjectPage.prototype.getPageCmd = function(){
	return 0;
}