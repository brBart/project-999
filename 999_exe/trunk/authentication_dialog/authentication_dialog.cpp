#include "authentication_dialog.h"

#include "../console/console_factory.h"

/**
 * @class AuthenticationDialog
 * Dialog for obtaining the username and password pair values.
 */

/**
 * Constructs the dialog.
 */
AuthenticationDialog::AuthenticationDialog(QWidget *parent, Qt::WindowFlags f)
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
AuthenticationDialog::~AuthenticationDialog()
{
	delete m_Console;
}

/**
 * Returns a pointer to the usernameLineEdit widget.
 */
QLineEdit* AuthenticationDialog::usernameLineEdit()
{
	return ui.usernameLineEdit;
}

/**
 * Returns a pointer to the passwordLineEdit widget.
 */
QLineEdit* AuthenticationDialog::passwordLineEdit()
{
	return ui.passwordLineEdit;
}

/**
 * Returns a pointer to the console.
 */
Console* AuthenticationDialog::console()
{
	return m_Console;
}
