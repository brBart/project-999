/*
 * sales_section.cpp
 *
 *  Created on: 08/05/2010
 *      Author: pc
 */

#include "sales_section.h"

#include <QList>
#include <QMessageBox>
#include <QInputDialog>
#include "../xml_transformer/xml_transformer_factory.h"
#include "../console/console_factory.h"
#include "../customer_dialog/customer_dialog.h"
#include "../registry.h"
#include "../discount_dialog/discount_dialog.h"
#include "cash_receipt_section.h"

/**
 * @class SalesSection
 * Section in charge of managing the invoice documents.
 */

/**
 * Constructs the section.
 */
SalesSection::SalesSection(QNetworkCookieJar *jar, QWebPluginFactory *factory,
		QUrl *serverUrl, QString cRegisterKey, QWidget *parent)
		: Section(jar, factory, serverUrl, parent), m_CRegisterKey(cRegisterKey)
{
	m_Window = dynamic_cast<MainWindow*>(parentWidget());
	ui.webView->setFocusPolicy(Qt::NoFocus);
	setActions();
	setMenu();
	setActionsManager();

	m_Console = ConsoleFactory::instance()->createHtmlConsole();
	m_Request = new HttpRequest(jar, this);
	m_Handler = new XmlResponseHandler(this);

	connect(ui.webView, SIGNAL(loadFinished(bool)), this,
			SLOT(loadFinished(bool)));
	connect(m_Handler, SIGNAL(sessionStatusChanged(bool)), this,
			SIGNAL(sessionStatusChanged(bool)));
	connect(&m_Recordset, SIGNAL(recordChanged(QString)), this,
			SLOT(fetchInvoice(QString)));

	m_Query = new QXmlQuery(QXmlQuery::XSLT20);

	fetchStyleSheet();
	refreshRecordset();

	if (m_Recordset.size() > 0) {
		m_Recordset.moveFirst();
	} else {
		fetchInvoiceForm();
	}
}

/**
 * Destroys the console object.
 */
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

	if (ok) {
		QWebFrame *frame = ui.webView->page()->mainFrame();
		m_CRegisterStatus =
				CRegisterStatus(frame->evaluateJavaScript("cashRegisterStatus")
						.toInt());
		m_DocumentStatus =
				DocumentStatus(frame->evaluateJavaScript("documentStatus").toInt());

		m_InvoiceKey = frame->evaluateJavaScript("objectKey").toString();
	} else {
		m_CRegisterStatus = Error;
	}

	m_Console->setFrame(ui.webView->page()->mainFrame());
	updateActions();

	// If a invoice was loaded.
	if (m_InvoiceKey != "")
		fetchInvoiceDetails(m_InvoiceKey);
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

	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("invoice");

	QString errorMsg;
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->content();
		QMap<QString, QString> *params = list[0];
		m_NewInvoiceKey = params->value("key");

		prepareInvoiceForm(params->value("date_time"), params->value("username"));
		fetchInvoiceDetails(m_NewInvoiceKey);

		m_DocumentStatus = Edit;
		updateActions();

		setCustomer();
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

	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("stub");

	QString errorMsg;
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		removeNewInvoiceFromSession();

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

/**
 * Sets a customer to the invoice in the server.
 */
void SalesSection::setCustomer()
{
	CustomerDialog dialog(m_Request->cookieJar(), m_ServerUrl, this,
			Qt::WindowTitleHint);

	if (dialog.exec() == QDialog::Accepted) {
		QUrl url(*m_ServerUrl);
		url.addQueryItem("cmd", "set_customer_invoice");
		url.addQueryItem("key", m_NewInvoiceKey);
		url.addQueryItem("customer_key", dialog.customerKey());
		url.addQueryItem("type", "xml");

		QString content = m_Request->get(url);

		XmlTransformer *transformer = XmlTransformerFactory::instance()
				->create("invoice_customer");

		QString errorMsg;
		if (m_Handler->handle(content, transformer, &errorMsg) ==
				XmlResponseHandler::Success) {
			QList<QMap<QString, QString>*> list = transformer->content();
			updateCustomerData(list[0]->value("nit"), list[0]->value("name"));
		} else {
			m_Console->displayError(errorMsg);
		}

		delete transformer;
	}
}

/**
 * Fetchs an invoice from the server.
 */
void SalesSection::fetchInvoice(QString id)
{
	// If there was an invoice on the session. Remove it.
	if (m_InvoiceKey != "")
		removeInvoiceFromSession();

	// Reinstall plugins because they will be lost on the page load.
	setPlugins();

	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "get_invoice");
	url.addQueryItem("id", id);
	url.addQueryItem("register_key", m_CRegisterKey);
	ui.webView->load(url);
}

/**
 * Adds a product to the invoice in the server.
 */
void SalesSection::addProductInvoice(int quantity)
{
	QString barCode = m_BarCodeLineEdit->text().trimmed();

	if (barCode != "") {
		QUrl url(*m_ServerUrl);
		url.addQueryItem("cmd", "add_product_invoice");
		url.addQueryItem("key", m_NewInvoiceKey);
		url.addQueryItem("bar_code", barCode);
		url.addQueryItem("quantity", QString::number(quantity));
		url.addQueryItem("type", "xml");

		QString content = m_Request->get(url);

		XmlTransformer *transformer = XmlTransformerFactory::instance()
				->create("stub");

		QString errorMsg, elementId;
		XmlResponseHandler::ResponseType response =
				m_Handler->handle(content, transformer, &errorMsg, &elementId);
		if (response == XmlResponseHandler::Success) {
			QApplication::beep();
			fetchInvoiceDetails(m_NewInvoiceKey);
			m_Console->cleanFailure("bar_code");
			m_BarCodeLineEdit->setText("");
		} else if (response == XmlResponseHandler::Failure) {
			m_Console->cleanFailure("bar_code");
			m_Console->displayFailure(errorMsg, elementId);
		} else {
			m_Console->displayError(errorMsg);
		}

		delete transformer;
	} else {
		m_BarCodeLineEdit->setText("");
	}
}

/**
 * Removes a product from the invoice on the server.
 */
void SalesSection::deleteProductInvoice()
{
	bool ok;
	int row = QInputDialog::getInt(this, "Quitar Producto", "Fila #:", 0, 1, 9999,
			1, &ok, Qt::WindowTitleHint);

	if (ok) {
		QWebElement tr = ui.webView->page()->mainFrame()
				->findFirstElement("#tr" + QString::number(row));

		if (!tr.isNull()) {
			QWebElement td = tr.findFirst("td");
			QString detailId = td.attribute("id");

			QUrl url(*m_ServerUrl);
			url.addQueryItem("cmd", "delete_product_invoice");
			url.addQueryItem("key", m_NewInvoiceKey);
			url.addQueryItem("detail_id", detailId);
			url.addQueryItem("type", "xml");

			QString content = m_Request->get(url);

			XmlTransformer *transformer = XmlTransformerFactory::instance()
					->create("stub");

			QString errorMsg;
			XmlResponseHandler::ResponseType response =
					m_Handler->handle(content, transformer, &errorMsg);
			if (response == XmlResponseHandler::Success) {
				fetchInvoiceDetails(m_NewInvoiceKey);
			} else if(response == XmlResponseHandler::Error) {
				m_Console->displayError(errorMsg);
			}

			delete transformer;
		}
	}
}

/**
 * Scrolls the detail's div element up.
 */
void SalesSection::scrollUp()
{
	QWebElement div = ui.webView->page()->mainFrame()->findFirstElement("#details");

	if (div.evaluateJavaScript("this.scrollTop").toInt() > 0)
		div.evaluateJavaScript("this.scrollTop -= 10");
}

/**
 * Scrolls the detail's div element down.
 */
void SalesSection::scrollDown()
{
	QWebElement div = ui.webView->page()->mainFrame()->findFirstElement("#details");

	if (div.evaluateJavaScript("this.scrollTop").toInt()
			< div.evaluateJavaScript("this.scrollHeight").toInt())
		div.evaluateJavaScript("this.scrollTop += 10");
}

/**
 * Shows the authentication dialog.
 */
void SalesSection::showAuthenticationDialog()
{
	m_AuthenticationDlg = new AuthenticationDialog(this, Qt::WindowTitleHint);
	m_AuthenticationDlg->setAttribute(Qt::WA_DeleteOnClose);
	m_AuthenticationDlg->setModal(true);

	connect(m_AuthenticationDlg, SIGNAL(okClicked()), this, SLOT(createDiscount()));

	m_AuthenticationDlg->show();
}

/**
 * Creates a discount on the server.
 */
void SalesSection::createDiscount()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "create_discount");
	url.addQueryItem("username", m_AuthenticationDlg->usernameLineEdit()->text());
	url.addQueryItem("password", m_AuthenticationDlg->passwordLineEdit()->text());
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("object_key");

	QString errorMsg;
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->content();
		QMap<QString, QString> *params = list[0];
		QString discountKey = params->value("key");
		m_AuthenticationDlg->close();

		DiscountDialog dlg(m_Request->cookieJar(), m_ServerUrl, discountKey, this,
				Qt::WindowTitleHint);

		if (dlg.exec() == QDialog::Accepted)
			setDiscountInvoice(discountKey);

	} else {
		m_AuthenticationDlg->passwordLineEdit()->setText("");
		m_AuthenticationDlg->usernameLineEdit()->selectAll();
		m_AuthenticationDlg->usernameLineEdit()->setFocus();
		m_AuthenticationDlg->console()->displayError(errorMsg);
	}

	delete transformer;
}

/**
 * Creates a cash receipt on the server.
 */
void SalesSection::createCashReceipt()
{
	bool errorFree = true;

	// If there is not a cash receipt already.
	if (m_CashReceiptKey == "") {
		QUrl url(*m_ServerUrl);
		url.addQueryItem("cmd", "create_cash_receipt");
		url.addQueryItem("invoice_key", m_NewInvoiceKey);
		url.addQueryItem("type", "xml");

		QString content = m_Request->get(url);

		XmlTransformer *transformer = XmlTransformerFactory::instance()
				->create("object_key");

		QString errorMsg, elementId;
		XmlResponseHandler::ResponseType response = m_Handler->handle(content,
			transformer, &errorMsg, &elementId);

		if (response == XmlResponseHandler::Success) {
			QList<QMap<QString, QString>*> list = transformer->content();
			QMap<QString, QString> *params = list[0];
			m_CashReceiptKey = params->value("key");
			m_Console->reset();
		} else if (response == XmlResponseHandler::Failure) {
			m_Console->reset();
			m_Console->displayFailure(errorMsg, elementId);
			errorFree = false;
		} else {
			m_Console->displayError(errorMsg);
			errorFree = false;
		}
	}

	if (errorFree) {
		QMainWindow *window = new QMainWindow(this, Qt::WindowTitleHint);
		window->setAttribute(Qt::WA_DeleteOnClose);
		window->setWindowModality(Qt::WindowModal);
		window->setWindowTitle("Recibo");
		window->resize(width() - (width() / 3), height() - 150);
		window->move(x() + (width() / 6), y() + 100);

		CashReceiptSection *section = new CashReceiptSection(
				ui.webView->page()->networkAccessManager()->cookieJar(),
				ui.webView->page()->pluginFactory(), m_ServerUrl, m_CashReceiptKey,
				m_NewInvoiceKey, window);

		connect(section, SIGNAL(cashReceiptSaved(QString)), this,
				SLOT(finishInvoice(QString)));

		window->setCentralWidget(section);
		window->show();

		section->loadUrl();
	}
}

/**
 * Prints and load the new created invoice from the server.
 */
void SalesSection::finishInvoice(QString id)
{
	removeNewInvoiceFromSession();

	printInvoice(id);

	refreshRecordset();
	m_Recordset.moveLast();
}

/**
 * Tests if there is a invoice on session, then unloads the section.
 */
void SalesSection::unloadSection()
{
	// If there was an invoice on the session. Remove it.
	if (m_InvoiceKey != "")
		removeInvoiceFromSession();

	m_Window->loadMainSection();
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
	connect(m_SaveAction, SIGNAL(triggered()), this, SLOT(createCashReceipt()));

	m_DiscardAction = new QAction("Cancelar", this);
	m_DiscardAction->setShortcut(tr("Ctrl+W"));
	connect(m_DiscardAction, SIGNAL(triggered()), this, SLOT(discardInvoice()));

	m_CancelAction = new QAction("Anular", this);
	m_CancelAction->setShortcut(Qt::Key_F10);

	m_ExitAction = new QAction("Salir", this);
	m_ExitAction->setShortcut(tr("Ctrl+Q"));
	connect(m_ExitAction, SIGNAL(triggered()), this, SLOT(unloadSection()));

	m_ClientAction = new QAction("Cliente", this);
	m_ClientAction->setShortcut(tr("Ctrl+E"));
	connect(m_ClientAction, SIGNAL(triggered()), this, SLOT(setCustomer()));

	m_DiscountAction = new QAction("Descuento", this);
	m_DiscountAction->setShortcut(Qt::Key_F7);
	connect(m_DiscountAction, SIGNAL(triggered()), this, SLOT(
			showAuthenticationDialog()));

	m_AddProductAction = new QAction("Agregar producto", this);
	m_AddProductAction->setShortcut(tr("Ctrl+I"));

	m_DeleteProductAction = new QAction("Quitar producto", this);
	m_DeleteProductAction->setShortcut(tr("Ctrl+D"));
	connect(m_DeleteProductAction, SIGNAL(triggered()), this,
			SLOT(deleteProductInvoice()));

	m_SearchProductAction = new QAction("Buscar producto", this);
	m_SearchProductAction->setShortcut(Qt::Key_F5);

	m_ScrollUpAction = new QAction("Desplazar arriba", this);
	m_ScrollUpAction->setShortcut(tr("Ctrl+Up"));
	connect(m_ScrollUpAction, SIGNAL(triggered()), this, SLOT(scrollUp()));

	m_ScrollDownAction = new QAction("Desplazar abajo", this);
	m_ScrollDownAction->setShortcut(tr("Ctrl+Down"));
	connect(m_ScrollDownAction, SIGNAL(triggered()), this, SLOT(scrollDown()));

	m_MoveFirstAction = new QAction("Primero", this);
	m_MoveFirstAction->setShortcut(tr("Home"));
	connect(m_MoveFirstAction, SIGNAL(triggered()), &m_Recordset,
			SLOT(moveFirst()));

	m_MovePreviousAction = new QAction("Anterior", this);
	m_MovePreviousAction->setShortcut(tr("PgUp"));
	connect(m_MovePreviousAction, SIGNAL(triggered()), &m_Recordset,
			SLOT(movePrevious()));

	m_MoveNextAction = new QAction("Siguiente", this);
	m_MoveNextAction->setShortcut(tr("PgDown"));
	connect(m_MoveNextAction, SIGNAL(triggered()), &m_Recordset, SLOT(moveNext()));

	m_MoveLastAction = new QAction("Ultimo", this);
	m_MoveLastAction->setShortcut(tr("End"));
	connect(m_MoveLastAction, SIGNAL(triggered()), &m_Recordset, SLOT(moveLast()));

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
	menu->addAction(m_DeleteProductAction);
	menu->addAction(m_SearchProductAction);

	menu = m_Window->menuBar()->addMenu("Ver");
	menu->addAction(m_ScrollUpAction);
	menu->addAction(m_ScrollDownAction);
	menu->addSeparator();
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
	*actions << m_DeleteProductAction;
	*actions << m_SearchProductAction;

	*actions << m_ScrollUpAction;
	*actions << m_ScrollDownAction;
	*actions << m_MoveFirstAction;
	*actions << m_MovePreviousAction;
	*actions << m_MoveNextAction;
	*actions << m_MoveLastAction;
	*actions << m_SearchAction;
	*actions << m_ConsultProductAction;

	m_ActionsManager.setActions(actions);
}

/**
 * Fetch the xslt style sheet from the server.
 */
void SalesSection::fetchStyleSheet()
{
	QUrl url = *(Registry::instance()->xslUrl());
	url.setPath(url.path() + "invoice_details.xsl");

	m_StyleSheet = m_Request->get(url);
}

/**
 * Sets the recordset.
 */
void SalesSection::refreshRecordset()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "get_invoice_list");
	url.addQueryItem("key", m_CRegisterKey);
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("invoice_list");

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
 * Fetchs an empty invoice form from the server.
 */
void SalesSection::fetchInvoiceForm()
{
	// Reinstall plugins because they will be lost on the page load.
	setPlugins();

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
				values = "011001111111000001";
				m_BarCodeLineEdit->setEnabled(true);
			} else {
				QString cancel =
						(m_DocumentStatus == Idle
								&& m_Recordset.size() > 0) ? "1" : "0";
				values = "100" + cancel + "100000" + navigateValues();
				m_BarCodeLineEdit->setEnabled(false);
			}
			break;

		case Closed:
			values = "0000100000" + navigateValues();
			m_BarCodeLineEdit->setEnabled(false);
			break;

		case Error:
			values = "000010000000000000";
			break;

		default:;
	}

	m_ActionsManager.updateActions(values);
}

/**
 * Auxiliary method for updating the QActions related to the recordset.
 */
QString SalesSection::navigateValues()
{
	if (m_Recordset.size() > 0) {
		if (m_Recordset.size() == 1) {
			return "11000001";
		} else if (m_Recordset.isFirst()) {
			return "11001111";
		} else if (m_Recordset.isLast()) {
			return "11110011";
		} else {
			return "11111111";
		}
	} else {
		return "00000001";
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

	element = frame->findFirstElement("#cash_amount");
	element.setInnerXml("0.00");

	element = frame->findFirstElement("#vouchers_total");
	element.setInnerXml("0.00");

	element = frame->findFirstElement("#change_amount");
	element.setInnerXml("0.00");

	m_RecordsetLabel->setText("");
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

/**
 * Updates the customer data on the webView object.
 */
void SalesSection::updateCustomerData(QString nit, QString name)
{
	QWebFrame *frame = ui.webView->page()->mainFrame();
	QWebElement element;

	element = frame->findFirstElement("#nit");
	element.setInnerXml(nit);

	element = frame->findFirstElement("#customer");
	element.setInnerXml(name + "&nbsp;");
}

/**
 * Installs the necessary plugins widgets in the plugin factory of the web view.
 */
void SalesSection::setPlugins()
{
	WebPluginFactory *factory =
				static_cast<WebPluginFactory*>(ui.webView->page()->pluginFactory());

	m_BarCodeLineEdit = new BarCodeLineEdit();
	factory->install("application/x-bar_code_line_edit", m_BarCodeLineEdit);

	m_RecordsetLabel = new Label();
	m_RecordsetLabel->setText(m_Recordset.text());
	factory->install("application/x-recordset", m_RecordsetLabel);

	connect(m_BarCodeLineEdit, SIGNAL(returnPressed()), this,
			SLOT(addProductInvoice()));
}

/**
 * Fetch the invoice details from the server.
 */
void SalesSection::fetchInvoiceDetails(QString invoiceKey)
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "get_invoice_details");
	url.addQueryItem("key", invoiceKey);
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	m_Query->setFocus(content);
	m_Query->setQuery(m_StyleSheet);

	QString result;
	m_Query->evaluateTo(&result);

	QWebElement div = ui.webView->page()->mainFrame()->findFirstElement("#details");
	div.setInnerXml(result);
	div.evaluateJavaScript("this.scrollTop = this.scrollHeight;");
}

/**
 * Sets a discount to an invoice on the server.
 */
void SalesSection::setDiscountInvoice(QString discountKey)
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "set_discount_invoice");
	url.addQueryItem("discount_key", discountKey);
	url.addQueryItem("key", m_NewInvoiceKey);
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	XmlTransformer *transformer = XmlTransformerFactory::instance()
				->create("stub");

	QString errorMsg;
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		fetchInvoiceDetails(m_NewInvoiceKey);
	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}

/**
 * Fetchs the invoice printing format and prints it.
 */
void SalesSection::printInvoice(QString id)
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "print_invoice");
	url.addQueryItem("id", id);

	QWebView *webView = new QWebView();

	webView->page()
			->setNetworkAccessManager(ui.webView->page()->networkAccessManager());
	webView->load(url);
	webView->resize(65, 50);

	webView->show();
}

/**
 * Removes the new invoice object from the session on the server.
 */
void SalesSection::removeNewInvoiceFromSession()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "remove_session_object");
	url.addQueryItem("key", m_NewInvoiceKey);
	url.addQueryItem("type", "xml");

	m_Request->get(url, true);

	// If a cash receipt was created, remove it from session.
	if (m_CashReceiptKey != "") {
		url = *m_ServerUrl;
		url.addQueryItem("cmd", "remove_session_object");
		url.addQueryItem("key", m_CashReceiptKey);
		url.addQueryItem("type", "xml");

		HttpRequest request(ui.webView->page()->networkAccessManager()
				->cookieJar(), this);

		request.get(url, true);

		m_CashReceiptKey = "";
	}
}

/**
 * Removes the invoice object from the session on the server.
 */
void SalesSection::removeInvoiceFromSession()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "remove_session_object");
	url.addQueryItem("key", m_InvoiceKey);
	url.addQueryItem("type", "xml");

	m_Request->get(url, true);
}
