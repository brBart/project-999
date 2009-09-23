/**
 * Library with the text range class.
 * @package Client
 * @author Roberto Oliveros
 */

/**
 * Constructor function.
 */
function TextRange(){}

/**
* Method that selects a range in the text object passed as parameter.
* @param object oText
* @param integer start
* @param integer length
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