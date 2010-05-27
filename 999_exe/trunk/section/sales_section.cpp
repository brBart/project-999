/*
 * sales_section.cpp
 *
 *  Created on: 08/05/2010
 *      Author: pc
 */

#include "sales_section.h"

/**
 * @class SalesSection
 * Section in charge of managing the invoice documents.
 */

#include <QList>
#include <QMessageBox>
#include "../xml_transformer/invoice_list_xml_transformer.h"
#include "../xml_transformer/invoice_xml_transformer.h"
#include "../xml_transformer/cash_register_status_xml_transformer.h"
#include "../xml_transformer/stub_xml_transformer.h"
#include "../console/console_factory.h"
#include "../customer_dialog/customer_dialog.h"
#include "../xml_transformer/invoice_customer_xml_transformer.h"

/**
 * Constructs the section.
 */
SalesSection::SalesSection(QNetworkAccessManager *manager,
		QWebPluginFactory *factory, QUrl *serverUrl, QString cRegisterKey,
		QWidget *parent) : Section(manager, factory, serverUrl, parent),
		m_CRegisterKey(cRegisterKey)
{
	m_Window = dynamic_cast<MainWindow*>(parentWidget());
	setActions();
	setMenu();
	setActionsManager();

	m_Console = ConsoleFactory::instance()->createHtmlConsole();
	m_Request = new HttpRequest(manager, this);
	m_Handler = new XmlResponseHandler(this);

	connect(ui.webView, SIGNAL(loadFinished(bool)), this,
			SLOT(loadFinished(bool)));
	connect(m_Handler, SIGNAL(sessionStatusChanged(bool)), this,
			SIGNAL(sessionStatusChanged(bool)));
	connect(&m_Recordset, SIGNAL(recordChanged(QString)), this,
			SLOT(fetchInvoice(QString)));

	refreshRecordset();
	if (m_Recordset.size() > 0) {
		m_Recordset.moveFirst();
	} else {
		fetchInvoiceForm();
	}
}

SalesSection::~SalesSection()
{
	delete m_Console;
}

/**
 * Updates the status of the section depending on the page received.
 */
void SalesSection::loadFinished(bool ok)
{
	Section::loadFinished(ok);
	m_Console->setFrame(ui.webView->page()->mainFrame());

	if (ok) {
		QWebFrame *frame = ui.webView->page()->mainFrame();
		m_CRegisterStatus =
				CRegisterStatus(frame->evaluateJavaScript("cashRegisterStatus")
						.toInt());
		m_DocumentStatus =
				DocumentStatus(frame->evaluateJavaScript("documentStatus").toInt());
	} else {
		m_CRegisterStatus = Error;
	}

	updateActions();
}

/**
 * Creates an invoice on the server.
 */
void SalesSection::createInvoice()
{
	m_Console->reset();

	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "create_invoice");
	url.addQueryItem("register_key", m_CRegisterKey);
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	QString errorMsg;
	InvoiceXmlTransformer *transformer = new InvoiceXmlTransformer();
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->content();
		QMap<QString, QString> *params = list[0];
		m_NewInvoiceKey = params->value("key");

		prepareInvoiceForm(params->value("date_time"), params->value("username"));
		//fetchInvoiceDetails();

		m_DocumentStatus = Edit;
		updateActions();

		setCustomer(false);
	} else {
		m_Console->displayError(errorMsg);
		fetchCashRegisterStatus();
	}

	delete transformer;
}

/**
 * Updates the cash register status received from the server.
 */
void SalesSection::updateCashRegisterStatus(QString content)
{
	// TODO: Test this if a closed cash register.
	QString errorMsg;
	CashRegisterStatusXmlTransformer *transformer =
			new CashRegisterStatusXmlTransformer();
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->content();
		QMap<QString, QString> *params = list[0];

		if (params->value("status") == "0") {
			QWebElement element = ui.webView->page()->mainFrame()
							->findFirstElement("#cash_register_status");
			element.setInnerXml("Cerrado");
			m_CRegisterStatus = Closed;
			updateActions();
		}
	}

	delete transformer;

	m_Request->disconnect(this);
}

/**
 * Discards a new invoice.
 */
void SalesSection::discardInvoice()
{
	if (QMessageBox::question(this, "Cancelar", "¿Esta seguro que desea salir sin "
			"guardar?", QMessageBox::Yes | QMessageBox::No) == QMessageBox::No)
		return;

	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "discard_document");
	url.addQueryItem("key", m_NewInvoiceKey);
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	QString errorMsg;
	StubXmlTransformer *transformer = new StubXmlTransformer();
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		url = *m_ServerUrl;
		url.addQueryItem("cmd", "remove_session_object");
		url.addQueryItem("key", m_NewInvoiceKey);
		url.addQueryItem("type", "xml");

		m_Request->get(url, true);

		if (m_Recordset.size() > 0) {
			m_Recordset.refresh();
		} else {
			fetchInvoiceForm();
		}
	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}

void SalesSection::setCustomer(bool isChanging)
{
	CustomerDialog dialog(ui.webView->page()->networkAccessManager(), m_ServerUrl,
			isChanging, this, Qt::CustomizeWindowHint | Qt::WindowTitleHint);

	if (dialog.exec() == QDialog::Accepted) {
		QUrl url(*m_ServerUrl);
		url.addQueryItem("cmd", "set_customer_invoice");
		url.addQueryItem("key", m_NewInvoiceKey);
		url.addQueryItem("customer_key", dialog.customerKey());
		url.addQueryItem("type", "xml");

		connect(m_Request, SIGNAL(finished(QString)), this,
				SLOT(updateCustomerData(QString)));

		QString content = m_Request->get(url, true);
	}
}

void SalesSection::updateCustomerData(QString content)
{
	QString errorMsg;
	InvoiceCustomerXmlTransformer *transformer =
			new InvoiceCustomerXmlTransformer();
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->content();

		QWebFrame *frame = ui.webView->page()->mainFrame();
		QWebElement element;

		element = frame->findFirstElement("#nit");
		element.setInnerXml(list[0]->value("nit"));

		element = frame->findFirstElement("#customer");
		element.setInnerXml(list[0]->value("name"));
	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;

	m_Request->disconnect(this);
}

/**
 * Fetchs an invoice from the server.
 */
void SalesSection::fetchInvoice(QString id)
{

}

/**
 * Creates the QActions for the menu bar.
 */
void SalesSection::setActions()
{
	m_NewAction = new QAction("Crear", this);
	m_NewAction->setShortcut(Qt::Key_Insert);
	connect(m_NewAction, SIGNAL(triggered()), this, SLOT(createInvoice()));

	m_SaveAction = new QAction("Guardar", this);
	m_SaveAction->setShortcut(tr("Ctrl+S"));

	m_DiscardAction = new QAction("Cancelar", this);
	m_DiscardAction->setShortcut(tr("Ctrl+W"));
	connect(m_DiscardAction, SIGNAL(triggered()), this, SLOT(discardInvoice()));

	m_CancelAction = new QAction("Anular", this);
	m_CancelAction->setShortcut(Qt::Key_F10);

	m_ExitAction = new QAction("Salir", this);
	m_ExitAction->setShortcut(tr("Ctrl+Q"));
	connect(m_ExitAction, SIGNAL(triggered()), m_Window, SLOT(loadMainSection()));

	m_ClientAction = new QAction("Cliente", this);
	m_ClientAction->setShortcut(tr("Ctrl+E"));
	connect(m_ClientAction, SIGNAL(triggered()), this, SLOT(setCustomer()));

	m_DiscountAction = new QAction("Descuento", this);
	m_DiscountAction->setShortcut(Qt::Key_F7);

	m_AddProductAction = new QAction("Agregar producto", this);
	m_AddProductAction->setShortcut(tr("Ctrl+I"));

	m_RemoveProductAction = new QAction("Quitar producto", this);
	m_RemoveProductAction->setShortcut(tr("Del"));

	m_SearchProductAction = new QAction("Buscar producto", this);
	m_SearchProductAction->setShortcut(Qt::Key_F5);

	m_MoveFirstAction = new QAction("Primero", this);
	m_MoveFirstAction->setShortcut(tr("Home"));

	m_MovePreviousAction = new QAction("Anterior", this);
	m_MovePreviousAction->setShortcut(tr("PgUp"));

	m_MoveNextAction = new QAction("Siguiente", this);
	m_MoveNextAction->setShortcut(tr("PgDown"));

	m_MoveLastAction = new QAction("Ultimo", this);
	m_MoveLastAction->setShortcut(tr("End"));

	m_SearchAction = new QAction("Buscar", this);
	m_SearchAction->setShortcut(Qt::Key_F1);

	m_ConsultProductAction = new QAction("Consultar producto", this);
	m_ConsultProductAction->setShortcut(Qt::Key_F6);
}

/**
 * Sets the window's menu bar.
 */
void SalesSection::setMenu()
{
	QMenu *menu;

	menu = m_Window->menuBar()->addMenu("Archivo");
	menu->addAction(m_NewAction);
	menu->addAction(m_SaveAction);
	menu->addAction(m_DiscardAction);
	menu->addAction(m_CancelAction);
	menu->addSeparator();
	menu->addAction(m_ExitAction);

	menu = m_Window->menuBar()->addMenu("Editar");
	menu->addAction(m_ClientAction);
	menu->addAction(m_DiscountAction);
	menu->addAction(m_AddProductAction);
	menu->addAction(m_RemoveProductAction);
	menu->addAction(m_SearchProductAction);

	menu = m_Window->menuBar()->addMenu("Ver");
	menu->addAction(m_MoveFirstAction);
	menu->addAction(m_MovePreviousAction);
	menu->addAction(m_MoveNextAction);
	menu->addAction(m_MoveLastAction);
	menu->addAction(m_SearchAction);
	menu->addSeparator();
	menu->addAction(m_ConsultProductAction);
}

/**
 * Sets the ActionsManager with the already created QActions.
 */
void SalesSection::setActionsManager()
{
	QList<QAction*> *actions = new QList<QAction*>();

	*actions << m_NewAction;
	*actions << m_SaveAction;
	*actions << m_DiscardAction;
	*actions << m_CancelAction;
	*actions << m_ExitAction;

	*actions << m_ClientAction;
	*actions << m_DiscountAction;
	*actions << m_AddProductAction;
	*actions << m_RemoveProductAction;
	*actions << m_SearchProductAction;

	*actions << m_MoveFirstAction;
	*actions << m_MovePreviousAction;
	*actions << m_MoveNextAction;
	*actions << m_MoveLastAction;
	*actions << m_SearchAction;
	*actions << m_ConsultProductAction;

	m_ActionsManager.setActions(actions);
}

/**
 * Sets the recordset.
 */
void SalesSection::refreshRecordset()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "get_invoice_list");
	url.addQueryItem("register_key", m_CRegisterKey);
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	QString errorMsg;
	InvoiceListXmlTransformer *transformer = new InvoiceListXmlTransformer();
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->content();
		m_Recordset.setList(list);
	}

	delete transformer;
}

/**
 * Fetchs an empty invoice form from the server.
 */
void SalesSection::fetchInvoiceForm()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "show_invoice_form");
	url.addQueryItem("register_key", m_CRegisterKey);
	ui.webView->load(url);
}

/**
 * Updates the QActions depending on the actual section status.
 */
void SalesSection::updateActions()
{
	QString values;

	switch (m_CRegisterStatus) {
		case Open:
			if (m_DocumentStatus == Edit) {
				values = "0110011111000001";
			} else {
				QString cancel =
						(m_DocumentStatus == Idle
								&& m_Recordset.size() > 0) ? "1" : "0";
				values = "100" + cancel + "100000" + viewValues();
			}
			break;

		case Closed:
			values = "0000100000" + viewValues();
			break;

		case Error:
			values = "0000100000000000";
			break;

		default:;
	}

	m_ActionsManager.updateActions(values);
}

/**
 * Auxialiry method for updating the QActions related to the recordset.
 */
QString SalesSection::viewValues()
{
	if (m_Recordset.size() > 0) {
		if (m_Recordset.isFirst()) {
			return "110011";
		} else if (m_Recordset.isLast()) {
			return "001111";
		} else {
			return "111111";
		}
	} else {
		return "000001";
	}
}

/**
 * Prepare the invoice form for creating a new invoice.
 */
void SalesSection::prepareInvoiceForm(QString dateTime, QString username)
{
	QWebFrame *frame = ui.webView->page()->mainFrame();
	QWebElement element;

	element = frame->findFirstElement("#status_label");
	element.setInnerXml("Creando...");

	element = frame->findFirstElement("#serial_number");
	element.setInnerXml("");

	element = frame->findFirstElement("#number");
	element.setInnerXml("");

	element = frame->findFirstElement("#date_time");
	element.setInnerXml(dateTime);

	element = frame->findFirstElement("#username");
	element.setInnerXml(username);

	// Change div css style from disabled to enabled.
	element = frame->findFirstElement("#main_data");
	element.removeClass("disabled");
	element.addClass("enabled");

	element = frame->findFirstElement("#nit_label");
	element.setInnerXml(element.toPlainText() + "*");

	element = frame->findFirstElement("#nit");
	element.setInnerXml("&nbsp;");

	element = frame->findFirstElement("#customer_label");
	element.setInnerXml(element.toPlainText() + "*");

	element = frame->findFirstElement("#customer");
	element.setInnerXml("&nbsp;");
}

/**
 * Fetchs the cash register status from the server.
 */
void SalesSection::fetchCashRegisterStatus()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "get_is_open_cash_register");
	url.addQueryItem("key", m_CRegisterKey);
	url.addQueryItem("type", "xml");

	connect(m_Request, SIGNAL(finished(QString)), this,
			SLOT(updateCashRegisterStatus(QString)));

	m_Request->get(url, true);
}
