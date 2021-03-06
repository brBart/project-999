/*
 * document_section.cpp
 *
 *  Created on: 26/08/2010
 *      Author: pc
 */

#include "document_section.h"

/**
 * @class DocumentSection
 * Base class with common functionality for the document derived sections.
 */

#include <QMessageBox>
#include <QInputDialog>
#include "../registry.h"
#include "../console/console_factory.h"
#include "../xml_transformer/xml_transformer_factory.h"

/**
 * Constructs the section.
 */
DocumentSection::DocumentSection(QNetworkCookieJar *jar, QWebPluginFactory *factory,
		QUrl *serverUrl, QString cashRegisterKey, QWidget *parent)
		: Section(jar, factory, serverUrl, parent),
		  m_CashRegisterKey(cashRegisterKey)
{
	m_Window = dynamic_cast<MainWindow*>(parentWidget());

	ui.webView->setFocusPolicy(Qt::NoFocus);

	m_Console = ConsoleFactory::instance()->createHtmlConsole();
	m_Request = new HttpRequest(jar, this);
	m_Handler = new XmlResponseHandler(this);

	connect(ui.webView, SIGNAL(loadFinished(bool)), this,
				SLOT(loadFinished(bool)));
	connect(m_Handler, SIGNAL(sessionStatusChanged(bool)), this,
			SIGNAL(sessionStatusChanged(bool)));
	connect(&m_Recordset, SIGNAL(recordChanged(QString)), this,
			SLOT(fetchDocument(QString)));

	m_Query = new QXmlQuery(QXmlQuery::XSLT20);
}

/**
 * Destroys the console and query objects.
 */
DocumentSection::~DocumentSection()
{
	delete m_Console;
	delete m_Query;
}

/**
 * Initialize the section.
 */
void DocumentSection::init()
{
	setActions();
	setMenu();
	setActionsManager();

	fetchStyleSheet();

	refreshRecordset();

	if (m_Recordset.size() > 0) {
		m_Recordset.moveFirst();
	} else {
		fetchDocumentForm();
	}
}

/**
 * Sets the name of the file with the style sheet on the server.
 */
void DocumentSection::setStyleSheetFileName(QString name)
{
	m_StyleSheetFileName = name;
}

/**
 * Sets the name of the command on the server for future use.
 */
void DocumentSection::setGetDocumentDetailsCmd(QString cmd)
{
	m_GetDocumentDetailsCmd = cmd;
}

/**
 * Sets the name of the command on the server for futuer use.
 */
void DocumentSection::setGetDocumentListCmd(QString cmd)
{
	m_GetDocumentListCmd = cmd;
}

/**
 * Sets the name of the command on the server for futuer use.
 */
void DocumentSection::setShowDocumentFormCmd(QString cmd)
{
	m_ShowDocumentFormCmd = cmd;
}

/**
 * Sets the name of the command on the server for futuer use.
 */
void DocumentSection::setGetDocumentCmd(QString cmd)
{
	m_GetDocumentCmd = cmd;
}

/**
 * Sets the name of the command on the server for futuer use.
 */
void DocumentSection::setCreateDocumentCmd(QString cmd)
{
	m_CreateDocumentCmd = cmd;
}

/**
 * Sets the name of the transformer to use.
 */
void DocumentSection::setDeleteItemDocumentCmd(QString cmd)
{
	m_DeleteItemDocumentCmd = cmd;
}

/**
 * Sets the name of the transformer to use.
 */
void DocumentSection::setCreateDocumentTransformerName(QString name)
{
	m_CreateDocumentTransformer = name;
}

/**
 * Sets the name of the transformer to use.
 */
void DocumentSection::setDocumentListTransformerName(QString name)
{
	m_DocumentListTransformer = name;
}

/**
 * Sets the name to use for the items for the delete items dialog title.
 */
void DocumentSection::setItemsName(QString name)
{
	m_ItemsName = name;
}

/**
 * Updates the status of the section depending on the page received.
 */
void DocumentSection::loadFinished(bool ok)
{
	Section::loadFinished(ok);

	if (ok) {
		QWebFrame *frame = ui.webView->page()->mainFrame();
		m_CashRegisterStatus =
				CashRegisterStatus(frame->evaluateJavaScript("cashRegisterStatus")
						.toInt());
		m_DocumentStatus =
				DocumentStatus(frame->evaluateJavaScript("documentStatus").toInt());

		m_DocumentKey = frame->evaluateJavaScript("objectKey").toString();
	} else {
		m_CashRegisterStatus = Error;
	}

	m_Console->setFrame(ui.webView->page()->mainFrame());

	// If a document was loaded.
	if (m_DocumentKey != "")
		fetchDocumentDetails(m_DocumentKey);

	updateActions();
}

/**
 * Fetchs a document from the server.
 */
void DocumentSection::fetchDocument(QString id)
{
	// If there was an invoice on the session. Remove it.
	if (m_DocumentKey != "")
		removeDocumentFromSession();

	// Reinstall plugins because they will be lost on the page load.
	setPlugins();

	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", m_GetDocumentCmd);
	url.addQueryItem("id", id);
	url.addQueryItem("register_key", m_CashRegisterKey);
	loadUrl(url);
}

/**
 * Tests if there is a document on session, then unloads the section.
 */
void DocumentSection::unloadSection()
{
	// If there was a document on the session. Remove it.
	if (m_DocumentKey != "")
		removeDocumentFromSession();

	m_Window->loadMainSection();
}

/**
 * Creates a document on the server.
 */
void DocumentSection::createDocument()
{
	m_Console->reset();

	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", m_CreateDocumentCmd);
	url.addQueryItem("register_key", m_CashRegisterKey);
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create(m_CreateDocumentTransformer);

	QString errorMsg;
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->content();
		QMap<QString, QString> *params = list[0];
		m_NewDocumentKey = params->value("key");

		prepareDocumentForm(params->value("username"));
		fetchDocumentDetails(m_NewDocumentKey);

		m_DocumentStatus = Edit;
		updateActions();

		createDocumentEvent(true, &list);
	} else {
		m_Console->displayError(errorMsg);
		fetchCashRegisterStatus();

		createDocumentEvent(false);
	}

	delete transformer;
}

/**
 * Updates the cash register status received from the server.
 */
void DocumentSection::updateCashRegisterStatus(QString content)
{
	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("cash_register_status");

	QString errorMsg;
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->content();
		QMap<QString, QString> *params = list[0];

		if (params->value("status") == "0") {
			QWebElement element = ui.webView->page()->mainFrame()
							->findFirstElement("#cash_register_status");
			element.setInnerXml("Cerrado");
			element.removeClass("pos_open_status");
			element.addClass("pos_closed_status");

			m_CashRegisterStatus = Closed;

			updateActions();
		}
	}

	delete transformer;

	m_Request->disconnect(this);
}

/**
 * Discards a new document.
 */
void DocumentSection::discardDocument()
{
	if (QMessageBox::question(this, "Cancelar", "�Esta seguro que desea salir sin "
			"guardar?", QMessageBox::Yes | QMessageBox::No) == QMessageBox::No)
		return;

	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "discard_document");
	url.addQueryItem("key", m_NewDocumentKey);
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("stub");

	QString errorMsg;
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		removeNewDocumentFromSession();

		if (m_Recordset.size() > 0) {
			m_Recordset.refresh();
		} else {
			fetchDocumentForm();
		}
	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}

/**
 * Scrolls the detail's div element up.
 */
void DocumentSection::scrollUp()
{
	QWebElement div = ui.webView->page()->mainFrame()->findFirstElement("#details");

	if (div.evaluateJavaScript("this.scrollTop").toInt() > 0)
		div.evaluateJavaScript("this.scrollTop -= 10");
}

/**
 * Scrolls the detail's div element down.
 */
void DocumentSection::scrollDown()
{
	QWebElement div = ui.webView->page()->mainFrame()->findFirstElement("#details");

	if (div.evaluateJavaScript("this.scrollTop").toInt()
			< div.evaluateJavaScript("this.scrollHeight").toInt())
		div.evaluateJavaScript("this.scrollTop += 10");
}

/**
 * Deletes the row from the document details on the server.
 */
void DocumentSection::deleteItemDocument()
{
	bool ok;
	int row = QInputDialog::getInt(this, "Quitar " + m_ItemsName ,
			"Fila #:", 0, 1, 9999, 1, &ok, Qt::WindowTitleHint);

	if (ok) {
		QWebElement tr = ui.webView->page()->mainFrame()
						->findFirstElement("#tr" + QString::number(row));

		if (!tr.isNull()) {
			QWebElement td = tr.findFirst("td");
			QString detailId = td.attribute("id");

			QUrl url(*m_ServerUrl);
			url.addQueryItem("cmd", m_DeleteItemDocumentCmd);
			url.addQueryItem("key", m_NewDocumentKey);
			url.addQueryItem("detail_id", detailId);
			url.addQueryItem("type", "xml");

			QString content = m_Request->get(url);

			XmlTransformer *transformer = XmlTransformerFactory::instance()
					->create("stub");

			QString errorMsg;
			XmlResponseHandler::ResponseType response =
					m_Handler->handle(content, transformer, &errorMsg);
			if (response == XmlResponseHandler::Success) {
				fetchDocumentDetails(m_NewDocumentKey);
			} else if(response == XmlResponseHandler::Error) {
				m_Console->displayError(errorMsg);
			}

			delete transformer;
		}
	}
}

/**
 * Changes the cash register to Loading state and loads a html page.
 */
void DocumentSection::loadUrl(QUrl url)
{
	m_CashRegisterStatus = Loading;

	updateActions();

	ui.webView->load(url);
}

/**
 * Sets the recordset.
 */
void DocumentSection::refreshRecordset()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", m_GetDocumentListCmd);
	url.addQueryItem("key", m_CashRegisterKey);
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create(m_DocumentListTransformer);

	QString errorMsg;
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->content();
		m_Recordset.setList(list);
	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}

/**
 * Fetch the document details from the server.
 */
void DocumentSection::fetchDocumentDetails(QString documentKey)
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", m_GetDocumentDetailsCmd);
	url.addQueryItem("key", documentKey);
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	// Must copy object to be reentrant and thread safe.
	QXmlQuery qry(*m_Query);
	qry.setFocus(content);
	qry.setQuery(m_StyleSheet);

	QString result;
	qry.evaluateTo(&result);

	QWebElement div = ui.webView->page()->mainFrame()->findFirstElement("#details");
	div.setInnerXml(result);
	div.evaluateJavaScript("this.scrollTop = this.scrollHeight;");
}

/**
 * Fetchs an empty document form from the server.
 */
void DocumentSection::fetchDocumentForm()
{
	// Reinstall plugins because they will be lost on the page load.
	setPlugins();

	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", m_ShowDocumentFormCmd);
	url.addQueryItem("register_key", m_CashRegisterKey);
	loadUrl(url);
}

/**
 * Removes the new document object from the session on the server.
 */
void DocumentSection::removeNewDocumentFromSession()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "remove_session_object");
	url.addQueryItem("key", m_NewDocumentKey);
	url.addQueryItem("type", "xml");

	m_Request->get(url, true);
}

/**
 * Prepare the document form for creating a new document.
 */
void DocumentSection::prepareDocumentForm(QString username)
{
	QWebFrame *frame = ui.webView->page()->mainFrame();
	QWebElement element;

	element = frame->findFirstElement("#status_label");
	element.setInnerXml("Creando...");
	element.removeClass("cancel_status");

	element = frame->findFirstElement("#date_time");
	element.setInnerXml("");

	element = frame->findFirstElement("#username");
	element.setInnerXml(username);
}

/**
 * Returns a pointer to the web plugin factory.
 */
WebPluginFactory* DocumentSection::webPluginFactory()
{
	return static_cast<WebPluginFactory*>(ui.webView->page()->pluginFactory());
}

/**
 * Installs the necessary plugins widgets in the plugin factory of the web view.
 */
void DocumentSection::setPlugins()
{
	m_RecordsetLabel = new Label();
	m_RecordsetLabel->setText(m_Recordset.text());
	webPluginFactory()->install("application/x-recordset", m_RecordsetLabel);
}

/**
 * Reimplement this method for extending functionality.
 */
void DocumentSection::createDocumentEvent(bool ok,
		QList<QMap<QString, QString>*> *list)
{

}

/**
 * Fetch the xslt style sheet from the server.
 */
void DocumentSection::fetchStyleSheet()
{
	QUrl url = *(Registry::instance()->xslUrl());
	url.setPath(url.path() + m_StyleSheetFileName);

	m_StyleSheet = m_Request->get(url);
}

/**
 * Removes the document object from the session on the server.
 */
void DocumentSection::removeDocumentFromSession()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "remove_session_object");
	url.addQueryItem("key", m_DocumentKey);
	url.addQueryItem("type", "xml");

	m_Request->get(url, true);
}

/**
 * Fetchs the cash register status from the server.
 */
void DocumentSection::fetchCashRegisterStatus()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "get_is_open_cash_register");
	url.addQueryItem("key", m_CashRegisterKey);
	url.addQueryItem("type", "xml");

	connect(m_Request, SIGNAL(finished(QString)), this,
			SLOT(updateCashRegisterStatus(QString)));

	m_Request->get(url, true);
}
