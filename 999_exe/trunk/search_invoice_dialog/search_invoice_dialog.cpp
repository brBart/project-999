#include "search_invoice_dialog.h"

/**
 * @class SearchProductDialog
 * Dialog for obtaining the serial number and number for the searched invoice.
 */

/**
 * Constructs the dialog.
 */
SearchInvoiceDialog::SearchInvoiceDialog(QWidget *parent, Qt::WindowFlags f)
    : QDialog(parent, f)
{
	ui.setupUi(this);
}

/**
 * Returns the serialNumberLineEdit text value.
 */
QString SearchInvoiceDialog::serialNumber()
{
	return ui.serialNumberLineEdit->text().trimmed();
}

/**
 * Returns the numberLineEdit text value.
 */
QString SearchInvoiceDialog::number()
{
	return ui.numberLineEdit->text().trimmed();
}
