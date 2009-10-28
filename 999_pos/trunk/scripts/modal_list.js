/**
 * @fileOverview Library with the ModalList class.
 * @author Roberto Oliveros
 */

/**
 * @class Represents a modal form with a list of details.
 * @constructor
 * @param {ObjectDetails} oStateMachine
 * @param {ModalForm} oForm
 */
function ModalList(oDetails, oForm){
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
}

/**
 * Shows list form.
 */
ModalList.prototype.showForm = function(){
	this._mDetails.update();
	this._mForm.show();
}

/**
 * Hides the list form.
 */
ModalList.prototype.hideForm = function(){
	this._mForm.hide();
}