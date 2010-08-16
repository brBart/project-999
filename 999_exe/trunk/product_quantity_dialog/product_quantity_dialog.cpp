#include "product_quantity_dialog.h"

/**
 * @class ProductQuantityDialog
 * Dialog for the user to input a product bar code and its quantity.
 */

/**
 * Constructs the dialog.
 */
ProductQuantityDialog::ProductQuantityDialog(QWidget *parent, Qt::WindowFlags f)
    : QDialog(parent, f)
{
	ui.setupUi(this);
	ui.quantitySpinBox->selectAll();
}

/**
 * Returns the bar code.
 */
QString ProductQuantityDialog::barCode()
{
	return ui.barCodeLineEdit->text();
}

/**
 * Returns the quantity.
 */
int ProductQuantityDialog::quantity()
{
	return ui.quantitySpinBox->value();
}
