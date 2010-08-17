#include "search_product_dialog.h"

#include "../console/console_factory.h"

/**
 * @class SearchProductDialog
 * Dialog for searching and obtaining a product's bar code.
 */

/**
 * Constructs the dialog.
 */
SearchProductDialog::SearchProductDialog(QNetworkCookieJar *jar, QUrl *url,
		QWidget *parent, Qt::WindowFlags f) : QDialog(parent, f)
{
	ui.setupUi(this);

	m_Console = ConsoleFactory::instance()
			->createWidgetConsole(QMap<QString, QLabel*>());
	m_Console->setFrame(ui.webView->page()->mainFrame());

	ui.nameLineEdit->setNetworkRequestObjects(jar, url, m_Console);
	ui.nameLineEdit->setDisplayWidget(ui.barCodeLineEdit);
}

/**
 * Destroys the console object.
 */
SearchProductDialog::~SearchProductDialog()
{
	delete m_Console;
}
