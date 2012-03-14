#include "cancel_invoice_dialog.h"

#include "../console/console_factory.h"

/**
 * @class CancelInvoiceDialog
 * Dialog for obtaining the username, password and reason values.
 */

/**
 * Constructs the dialog.
 */
CancelInvoiceDialog::CancelInvoiceDialog(QWidget *parent, Qt::WindowFlags f)
    : QDialog(parent, f)
{
	ui.setupUi(this);

	m_Console = ConsoleFactory::instance()
			->createWidgetConsole(QMap<QString, QLabel*>());
	m_Console->setFrame(ui.webView->page()->mainFrame());

	connect(ui.okPushButton, SIGNAL(clicked()), this, SIGNAL(okClicked()));
	connect(ui.cancelPushButton, SIGNAL(clicked()), this, SLOT(close()));
}

/**
 * Destroys the console.
 */
CancelInvoiceDialog::~CancelInvoiceDialog()
{
	delete m_Console;
}

/**
 * Returns a pointer to the usernameLineEdit widget.
 */
QLineEdit* CancelInvoiceDialog::usernameLineEdit()
{
	return ui.usernameLineEdit;
}

/**
 * Returns a pointer to the passwordLineEdit widget.
 */
QLineEdit* CancelInvoiceDialog::passwordLineEdit()
{
	return ui.passwordLineEdit;
}

/**
 * Returns a pointer to the reasonLineEdit widget.
 */
QLineEdit* CancelInvoiceDialog::reasonLineEdit()
{
	return ui.reasonLineEdit;
}

/**
 * Returns a pointer to the console.
 */
Console* CancelInvoiceDialog::console()
{
	return m_Console;
}
