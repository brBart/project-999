/**
 * @fileOverview Library with the ModalPage class.
 * @author Roberto Oliveros
 */

/**
 * @class Represents a modal form with a page of details.
 * @constructor
 * @param {ObjectPage} oPage
 * @param {ModalForm} oForm
 * @param {Command} oCommand
 */
function ModalList(oPage, oForm, oCommand){
	/**
	 * Holds a reference to the page object.
	 * @type ObjectPage
	 */
	this._mPage = oPage;
	
	/**
	 * Holds a reference to the modal form object.
	 * @type ModalForm
	 */
	this._mForm = oForm;
	
	/**
	 * Holds the command object to execute after the form is hidden.
	 * @type Command
	 */
	this._mCommand = oCommand;
}

/**
 * Shows list form.
 */
ModalList.prototype.showForm = function(){
	this._mPage.getLastPage();
	this._mForm.show();
}

/**
 * Hides the list form and executes the command object.
 */
ModalList.prototype.hideForm = function(){
	this._mForm.hide();
	if(this._mCommand != null)
		this._mCommand.execute();
}