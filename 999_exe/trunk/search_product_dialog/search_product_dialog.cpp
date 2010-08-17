#include "search_product_dialog.h"

#include <QList>
#include <QStandardItem>
#include <QTreeView>
#include "../console/console_factory.h"
#include "../xml_transformer/xml_transformer_factory.h"

/**
 * @class SearchProductDialog
 * Dialog for searching and obtaining a product's bar code.
 */

/**
 * Constructs the dialog.
 */
SearchProductDialog::SearchProductDialog(QNetworkCookieJar *jar, QUrl *url,
		QWidget *parent, Qt::WindowFlags f) : QDialog(parent, f), m_ServerUrl(url)
{
	ui.setupUi(this);

	m_Console = ConsoleFactory::instance()
			->createWidgetConsole(QMap<QString, QLabel*>());
	m_Console->setFrame(ui.webView->page()->mainFrame());

	m_Request = new HttpRequest(jar, this);
	m_Handler = new XmlResponseHandler(this);

	m_Model.setColumnCount(3);
	m_Completer = new QCompleter(&m_Model, this);

	QTreeView *tree = new QTreeView(this);
	tree->hideColumn(2);
	m_Completer->setPopup(tree);

	connect(m_Handler, SIGNAL(sessionStatusChanged(bool)), this,
			SIGNAL(sessionStatusChanged(bool)));
	connect(m_Request, SIGNAL(finished(QString)), this,
			SLOT(updateProductModel(QString)));
	connect(ui.nameLineEdit, SIGNAL(textEdited()), &m_CheckerTimer, SLOT(start()));
	connect(&m_CheckerTimer, SIGNAL(timeout()), this, SLOT(checkForChanges()));
	connect(&m_SenderTimer, SIGNAL(timeout()), this, SLOT(fetchProducts()));

	m_CheckerTimer.setInterval(500);
	m_SenderTimer.setInterval(500);
	m_SenderTimer.setSingleShot(true);
}

/**
 * Destroys the console object.
 */
SearchProductDialog::~SearchProductDialog()
{
	delete m_Console;
}

/**
 * Checks if the name has change.
 * If the name value has change and there is no current completion on the completer
 * object then fetch for more products from the server.
 */
void SearchProductDialog::checkForChanges()
{
	if (ui.nameLineEdit->text() != "" && m_Completer->currentCompletion() == "") {
		m_Name = ui.nameLineEdit->text();
		m_NamesQueue.enqueue(m_Name);
		fetchProducts();
	}
}

/**
 * Fetch for more products' names for matching the product name is being search for.
 */
void SearchProductDialog::fetchProducts()
{
	if (!m_Request->isBusy()) {
		QUrl url(*m_ServerUrl);
		url.addQueryItem("cmd", "search_product");
		url.addQueryItem("keyword", m_NamesQueue.dequeue());
		url.addQueryItem("type", "xml");

		m_Request->get(url, true);
	} else {
		// If there was already a waiting call, clean it.
		if (m_SenderTimer.timerId() > -1) {
			m_NamesQueue.dequeue();
			m_SenderTimer.stop();
		}

		m_SenderTimer.start();
	}
}

/**
 * Updates the products name model to match the product's name is being search for.
 */
void SearchProductDialog::updateProductModel(QString content)
{
	XmlTransformer *transformer = XmlTransformerFactory::instance()
				->create("search_product_results");

	QString errorMsg;
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {

		QList<QMap<QString, QString>*> list = transformer->content();
		QMap<QString, QString> *map;

		QString keyword = list[0]->value("keyword");

		QList<QStandardItem*> *itemList;
		for (int i = 1; i < list.size(); i++) {
			map = list[i];

			itemList = new QList<QStandardItem*>;
			itemList->append(new QStandardItem(map->value("name")));
			itemList->append(new QStandardItem(map->value("packaging")));
			itemList->append(new QStandardItem(map->value("bar_code")));

			m_Model.appendRow(*itemList);
		}

		// Display the drop down list if the name value has not change.
		if (keyword == ui.nameLineEdit->text())
			m_Completer->popup()->show();

	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}
