/**
 * @fileOverview Library with the ModalForm class.
 * @author Roberto Oliveros
 */

/**
 * @class Represents a modal form on the screen.
 * @constructor
 * @param {String} sForm The id of the div element.
 */
function ModalForm(sForm){
	/**
	 * Holds a reference to the div element with the form element.
	 * @type HtmlElement
	 */
	this._mForm = document.getElementById(sForm);
}

/**
* Shows the form div element.
*/
ModalForm.prototype.show = function(){
	// Keep the largest height.
	if(document.getElementById('wrapper').scrollHeight > document.body.scrollHeight)
		this._mForm.style.height = document.getElementById('wrapper').scrollHeight + 'px';
	else
		this._mForm.style.height = document.body.scrollHeight + 'px';
	
	this._mForm.style.width = document.body.scrollWidth + 'px';

	this._mForm.className = 'modal_form';
}

/**
* Hides the form div element.
*/
ModalForm.prototype.hide = function(){
	this._mForm.className = 'hidden';
}