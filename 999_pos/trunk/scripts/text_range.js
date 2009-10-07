/**
 * @fileOverview Library with the TextRange class.
 * @author Roberto Oliveros
 */

/**
 * @class Selects a range of characters on an input element.
 * @constructor
 */
function TextRange(){}

/**
 * Method that selects a range in the text input element passed as parameter.
 * @param {HtmlElement} oText
 * @param {Integer} start From where the selection will begin.
 * @param {Integer} length Where the selection ends.
 */
TextRange.selectRange = function(oText, start, length){
	// check to see if in IE or FF
	if (oText.createTextRange)
	{
		//IE
		var oRange = oText.createTextRange(); 
		oRange.moveStart('character', start); 
		oRange.moveEnd('character', length); 
		oRange.select();
	}
	else 
		// FF
		if (oText.setSelectionRange) 
		{
			oText.setSelectionRange(start, length);
		}
}