/**
 * @fileOverview Library with the ModalList class.
 * @author Roberto Oliveros
 */

/**
 * @class Represents a modal form with a list of details.
 * @constructor
 * @param {ObjectDetails} oDetails
 * @param {ModalForm} oForm
 * @param {Command} oCommand
 */
function ModalList(oDetails, oForm, oCommand){
	/**
	 * Holds a reference to the details object.
	 * @type ObjectDetails
	 */
	this._mDetails = oDetails;
	
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
	this._mDetails.update();
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