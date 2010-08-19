#include "search_product_dialog.h"

#include "../console/console_factory.h"
#include "../enter_key_event_filter/enter_key_event_filter.h"

/**
 * @class SearchProductDialog
 * Dialog for searching and obtaining a product's bar code.
 */

/**
 * Constructs the dialog.
 */
SearchProductDialog::SearchProductDialog(QNetworkCookieJar *jar, QUrl *url,
		SearchProductModel *model, QWidget *parent, Qt::WindowFlags f)
		: QDialog(parent, f)
{
	ui.setupUi(this);

	m_Console = ConsoleFactory::instance()
			->createWidgetConsole(QMap<QString, QLabel*>());
	m_Console->setFrame(ui.webView->page()->mainFrame());

	ui.quantitySpinBox->selectAll();

	ui.nameSearchProductLineEdit->init(jar, url, m_Console, model);

	ui.cancelPushButton->installEventFilter(new EnterKeyEventFilter(this));

	connect(ui.nameSearchProductLineEdit, SIGNAL(activated()), this, SLOT(accept()));
}

/**
 * Destroys the console object.
 */
SearchProductDialog::~SearchProductDialog()
{
	delete m_Console;
}

/**
 * Returns the bar code of the searched product.
 */
QString SearchProductDialog::barCode()
{
	return ui.nameSearchProductLineEdit->barCode();
}

/**
 * Returns the quantity of the searched product.
 */
int SearchProductDialog::quantity()
{
	return ui.quantitySpinBox->value();
}
