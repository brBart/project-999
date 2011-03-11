/*
 * search_product_line_edit.cpp
 *
 *  Created on: 17/08/2010
 *      Author: pc
 */

#include "search_product_line_edit.h"

#include <QList>
#include <QTreeView>
#include <QCompleter>
#include "../xml_transformer/xml_transformer_factory.h"

/**
 * @class SearchProductLineEdit
 * Widget for searching for a product's bar code by its name.
 */

/**
 * Constructs the widget.
 */
SearchProductLineEdit::SearchProductLineEdit(QWidget *parent) : QLineEdit(parent)
{

}

/**
 * Sets the necessary objects to communicate with the server.
 */
void SearchProductLineEdit::init(QNetworkCookieJar *jar, QUrl *url,
		Console *console, SearchProductModel *model, bool includeDeactivated)
{
	m_Request = new HttpRequest(jar, this);
	m_Handler = new XmlResponseHandler(this);
	m_ServerUrl = url;
	m_Console = console;

	connect(m_Handler, SIGNAL(sessionStatusChanged(bool)), this,
				SIGNAL(sessionStatusChanged(bool)));
	connect(m_Request, SIGNAL(finished(QString)), this,
			SLOT(updateProductModel(QString)));

	m_Keywords = model->keywords();
	m_Model = model;
	m_IncludeDeactivated = includeDeactivated;

	QTreeView *tree = new QTreeView(this);
	tree->setHeaderHidden(true);
	tree->setIndentation(0);

	QCompleter *completer = new QCompleter(m_Model, this);
	completer->setPopup(tree);
	completer->setCaseSensitivity(Qt::CaseInsensitive);

	setCompleter(completer);

	tree->hideColumn(3);

	connect(&m_CheckerTimer, SIGNAL(timeout()), this, SLOT(checkForChanges()));
	connect(&m_SenderTimer, SIGNAL(timeout()), this, SLOT(fetchProducts()));

	m_CheckerTimer.setInterval(500);
	m_SenderTimer.setInterval(500);
	m_SenderTimer.setSingleShot(true);
}

/**
 * Returns the bar code of the searched product.
 */
QString SearchProductLineEdit::barCode()
{
	return m_BarCode;
}

/**
 * Checks if the name has change.
 * If the name value has change and there is no current completion on the completer
 * object then fetch for more products from the server.
 */
void SearchProductLineEdit::checkForChanges()
{
	QString keyword = text();

	if (keyword != "" && (m_Keywords->indexOf(keyword) == -1)) {
		m_NamesQueue.enqueue(keyword);
		fetchProducts();
	}
}

/**
 * Fetch for more products' names for matching the product name is being search for.
 */
void SearchProductLineEdit::fetchProducts()
{
	if (!m_Request->isBusy()) {
		QUrl url(*m_ServerUrl);
		url.addQueryItem("cmd", "search_product");
		url.addQueryItem("keyword", m_NamesQueue.dequeue());
		url.addQueryItem("include_deactivated", m_IncludeDeactivated ? "1" : "0");
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
void SearchProductLineEdit::updateProductModel(QString content)
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

			if ((m_Model->findItems(map->value("name"))).size() == 0) {
				itemList = new QList<QStandardItem*>;
				itemList->append(new QStandardItem(map->value("name")));
				itemList->append(new QStandardItem(map->value("packaging")));
				itemList->append(new QStandardItem(map->value("manufacturer")));
				itemList->append(new QStandardItem(map->value("bar_code")));

				m_Model->appendRow(*itemList);
				// Add name to the keywords so we don't have to search it againg.
				*m_Keywords << map->value("name");
			}
		}

		m_Model->sort(0, Qt::AscendingOrder);

		*m_Keywords << keyword;

		// Display the drop down list if the name value has not change.
		if (keyword == text())
			completer()->complete();

	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}

/**
 * Emits the activated signal with the bar code string value chosen.
 */
void SearchProductLineEdit::itemChose(const QModelIndex &index)
{
	m_BarCode = completer()->completionModel()->index(index.row(), 3)
			.data().toString();

	emit activated();
}

/**
 * Start listening to the name values changes.
 */
void SearchProductLineEdit::focusInEvent(QFocusEvent *e)
{
	// Has to be here, don't know why.
	connect(completer(), SIGNAL(activated(const QModelIndex&)), this,
			SLOT(itemChose(const QModelIndex&)));

	m_CheckerTimer.start();
	QLineEdit::focusInEvent(e);
}

/**
 * Stop listening to name values changes.
 */
void SearchProductLineEdit::focusOutEvent(QFocusEvent *e)
{
	m_CheckerTimer.stop();
	QLineEdit::focusOutEvent(e);
}
