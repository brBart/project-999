/*
 * sales_section.cpp
 *
 *  Created on: 08/05/2010
 *      Author: pc
 */

#include "sales_section.h"

#include <QList>
#include <QPrinter>
#include <QMessageBox>
#include "../xml_transformer/xml_transformer_factory.h"
#include "../customer_dialog/customer_dialog.h"
#include "../registry.h"
#include "../discount_dialog/discount_dialog.h"
#include "cash_receipt_section.h"
#include "../product_quantity_dialog/product_quantity_dialog.h"
#include "../search_product_dialog/search_product_dialog.h"
#include "../search_product/search_product_model.h"
#include "../recordset/recordset_searcher_factory.h"
#include "../search_invoice_dialog/search_invoice_dialog.h"
#include "../consult_product_dialog/consult_product_dialog.h"
#include "../printer_status_handler/printer_status_handler.h"

/**
 * @class SalesSection
 * Section in charge of managing the invoice documents.
 */

/**
 * Constructs the section.
 */
SalesSection::SalesSection(QNetworkCookieJar *jar, QWebPluginFactory *factory,
		QUrl *serverUrl, QString cashRegisterKey, QWidget *parent)
		: DocumentSection(jar, factory, serverUrl, cashRegisterKey, parent)
{

}

/**
 * Sets a customer to the invoice in the server.
 */
void SalesSection::setCustomer()
{
	CustomerDialog dialog(m_Request->cookieJar(), m_ServerUrl, this,
			Qt::WindowTitleHint);

	connect(&dialog, SIGNAL(sessionStatusChanged(bool)), this,
			SIGNAL(sessionStatusChanged(bool)), Qt::QueuedConnection);

	if (dialog.exec() == QDialog::Accepted) {
		QUrl url(*m_ServerUrl);
		url.addQueryItem("cmd", "set_customer_invoice");
		url.addQueryItem("key", m_NewDocumentKey);
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
			m_Console->cleanFailure("nit");
		} else {
			m_Console->displayError(errorMsg);
		}

		delete transformer;
	}
}

/**
 * Adds a product to the invoice in the server.
 */
void SalesSection::addProductInvoice(QString barCode, int quantity)
{
	barCode = barCode.trimmed();

	if (barCode != "") {
		QUrl url(*m_ServerUrl);
		url.addQueryItem("cmd", "add_product_invoice");
		url.addQueryItem("key", m_NewDocumentKey);
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
			fetchDocumentDetails(m_NewDocumentKey);
			m_Console->reset();
			m_BarCodeLineEdit->setText("");
		} else if (response == XmlResponseHandler::Failure) {
			m_Console->cleanFailure(elementId);
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
 * Shows the authentication dialog to authorize a discount.
 */
void SalesSection::showAuthenticationDialogForDiscount()
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

		connect(&dlg, SIGNAL(sessionStatusChanged(bool)), this,
				SIGNAL(sessionStatusChanged(bool)), Qt::QueuedConnection);

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
 * Validates the invoice before creating the cash receipt.
 */
void SalesSection::validate()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "validate_invoice");
	url.addQueryItem("invoice_key", m_NewDocumentKey);
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("stub");

	QString errorMsg, elementId;
	XmlResponseHandler::ResponseType response = m_Handler->handle(content,
		transformer, &errorMsg, &elementId);

	if (response == XmlResponseHandler::Success) {
		m_Console->reset();

		showCashReceipt();

	} else if (response == XmlResponseHandler::Failure) {
		m_Console->reset();
		m_Console->displayFailure(errorMsg, elementId);
	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}

/**
 * Prints and load the new created invoice from the server.
 */
void SalesSection::finishInvoice(QString id)
{
	removeNewDocumentFromSession();

	printInvoice(id);

	refreshRecordset();
	m_Recordset.moveLast();
}

/**
 * Shows the ProductQuantityDialog to enter a bar code and a quantity value.
 */
void SalesSection::addProductWithQuantity()
{
	ProductQuantityDialog dialog(this, Qt::WindowTitleHint);

	if (dialog.exec() == QDialog::Accepted) {
		// The bar code text set to the line edit in case of fail validation
		// retrospective.
		m_BarCodeLineEdit->setText(dialog.barCode());
		addProductInvoice(dialog.barCode(), dialog.quantity());
	}
}

/**
 * Searchs for a product's bar code by name and adds it to the invoice.
 */
void SalesSection::searchProduct()
{
	SearchProductDialog dialog(m_Request->cookieJar(), m_ServerUrl,
			SearchProductModel::instance(), this, Qt::WindowTitleHint);

	connect(&dialog, SIGNAL(sessionStatusChanged(bool)), this,
			SIGNAL(sessionStatusChanged(bool)), Qt::QueuedConnection);

	if (dialog.exec() == QDialog::Accepted) {
		// The bar code text set to the line edit in case of fail validation
		// retrospective.
		m_BarCodeLineEdit->setText(dialog.barCode());
		addProductInvoice(dialog.barCode(), dialog.quantity());
	}
}

/**
 * Shows the search dialog and pass the search criteria to the recordset.
 */
void SalesSection::searchInvoice()
{
	SearchInvoiceDialog dialog(this, Qt::WindowTitleHint);

	if (dialog.exec() == QDialog::Accepted) {
		QString value = dialog.serialNumber() + " " + dialog.number();

		if (value.trimmed() != "") {
			RecordsetSearcher *searcher =
					RecordsetSearcherFactory::instance()->create("invoice");

			m_Recordset.installSearcher(searcher);

			if (!m_Recordset.search(value))
				m_Console->displayError("Factura no se encuentra en esta caja.");

			delete searcher;
		}
	}
}

/**
 * Shows the consult product dialog for searching for certain product.
 */
void SalesSection::consultProduct()
{
	ConsultProductDialog dialog(m_Request->cookieJar(), m_ServerUrl,
			SearchProductModel::instance(), this, Qt::WindowTitleHint);
	dialog.exec();
}

/**
 * Opens a dialog showing the vouchers used on this invoice's cash receipt.
 */
void SalesSection::showVouchers()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "show_invoice_cash_receipt_vouchers");
	url.addQueryItem("key", m_DocumentKey);

	QDialog dialog(this, Qt::WindowTitleHint);
	dialog.setWindowTitle("Tarjetas");
	QHBoxLayout *layout = new QHBoxLayout(&dialog);

	QWebView view;
	view.page()->networkAccessManager()->setCookieJar(m_Request->cookieJar());
	m_Request->cookieJar()->setParent(0);
	view.load(url);

	layout->addWidget(&view);
	dialog.exec();
}

/**
 * It verifies if the printer is ready.
 */
void SalesSection::checkPrinterForCancel()
{
	Registry *registry = Registry::instance();

	if (registry->isTMUPrinter()) {
		PrinterStatusHandler printerHandler(registry->printerName());
		QString readyMsg;
		bool printerOk = false;

		do {
			printerOk = printerHandler.isReady(&readyMsg);
			if (!printerOk) {
				if (QMessageBox::critical(this, "Impresora", "Impresora no esta lista: " +
						readyMsg + " Presione Aceptar cuando este lista para poder "
						"continuar o Cancelar para regresar.",
						QMessageBox::Ok | QMessageBox::Cancel) == QMessageBox::Cancel)
					return;
			}
		} while(!printerOk);
	}

	showAuthenticationDialogForCancel();
}

/**
 * Creates the QActions for the menu bar.
 */
void SalesSection::setActions()
{
	m_NewAction = new QAction("Crear", this);
	m_NewAction->setShortcut(Qt::Key_Insert);
	connect(m_NewAction, SIGNAL(triggered()), this, SLOT(createDocument()));

	m_SaveAction = new QAction("Guardar", this);
	m_SaveAction->setShortcut(tr("Ctrl+S"));
	connect(m_SaveAction, SIGNAL(triggered()), this, SLOT(validate()));

	m_DiscardAction = new QAction("Cancelar", this);
	m_DiscardAction->setShortcut(Qt::Key_Escape);
	connect(m_DiscardAction, SIGNAL(triggered()), this, SLOT(discardDocument()));

	m_CancelAction = new QAction("Anular", this);
	m_CancelAction->setShortcut(Qt::Key_F12);
	connect(m_CancelAction, SIGNAL(triggered()), this,
			SLOT(checkPrinterForCancel()));

	m_ExitAction = new QAction("Salir", this);
	m_ExitAction->setShortcut(Qt::Key_Escape);
	connect(m_ExitAction, SIGNAL(triggered()), this, SLOT(unloadSection()));

	m_ClientAction = new QAction("Cliente", this);
	m_ClientAction->setShortcut(tr("Ctrl+E"));
	connect(m_ClientAction, SIGNAL(triggered()), this, SLOT(setCustomer()));

	m_DiscountAction = new QAction("Descuento", this);
	m_DiscountAction->setShortcut(Qt::Key_F7);
	connect(m_DiscountAction, SIGNAL(triggered()), this, SLOT(
			showAuthenticationDialogForDiscount()));

	m_AddItemAction = new QAction("Agregar producto", this);
	m_AddItemAction->setShortcut(tr("Ctrl+I"));
	connect(m_AddItemAction, SIGNAL(triggered()), this, SLOT(
			addProductWithQuantity()));

	m_DeleteItemAction = new QAction("Quitar producto", this);
	m_DeleteItemAction->setShortcut(tr("Ctrl+D"));
	connect(m_DeleteItemAction, SIGNAL(triggered()), this,
			SLOT(deleteItemDocument()));

	m_SearchProductAction = new QAction("Buscar producto", this);
	m_SearchProductAction->setShortcut(Qt::Key_F5);
	connect(m_SearchProductAction, SIGNAL(triggered()), this,
				SLOT(searchProduct()));

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
	connect(m_SearchAction, SIGNAL(triggered()), this, SLOT(searchInvoice()));

	m_ViewVouchers = new QAction("Tarjetas", this);
	m_ViewVouchers->setShortcut(tr("Ctrl+T"));
	connect(m_ViewVouchers, SIGNAL(triggered()), this, SLOT(showVouchers()));

	m_ConsultProductAction = new QAction("Consultar producto", this);
	m_ConsultProductAction->setShortcut(Qt::Key_F6);
	connect(m_ConsultProductAction, SIGNAL(triggered()), this,
			SLOT(consultProduct()));
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
	menu->addAction(m_AddItemAction);
	menu->addAction(m_DeleteItemAction);
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
	menu->addAction(m_ViewVouchers);
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
	*actions << m_AddItemAction;
	*actions << m_DeleteItemAction;
	*actions << m_SearchProductAction;

	*actions << m_ScrollUpAction;
	*actions << m_ScrollDownAction;
	*actions << m_MoveFirstAction;
	*actions << m_MovePreviousAction;
	*actions << m_MoveNextAction;
	*actions << m_MoveLastAction;
	*actions << m_SearchAction;
	*actions << m_ViewVouchers;
	*actions << m_ConsultProductAction;

	m_ActionsManager.setActions(actions);
}

/**
 * Installs the necessary plugins widgets in the plugin factory of the web view.
 */
void SalesSection::setPlugins()
{
	DocumentSection::setPlugins();

	m_BarCodeLineEdit = new BarCodeLineEdit();
	webPluginFactory()
			->install("application/x-bar_code_line_edit", m_BarCodeLineEdit);

	connect(m_BarCodeLineEdit, SIGNAL(returnPressedBarCode(QString)), this,
			SLOT(addProductInvoice(QString)));
}

/**
 * Updates the QActions depending on the actual section status.
 */
void SalesSection::updateActions()
{
	QString values;

	switch (m_CashRegisterStatus) {
		case Open:
			if (m_DocumentStatus == Edit) {
				values = "0110011111110000001";
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
			values = "0000100000000000000";
			break;

		case Loading:
			values = "0000000000000000000";
			break;

		default:;
	}

	m_ActionsManager.updateActions(values);
}

/**
 * Removes the new cash receipt object from the session on the server.
 */
void SalesSection::removeNewDocumentFromSession()
{
	DocumentSection::removeNewDocumentFromSession();

	// If a cash receipt was created, remove it from session.
	if (m_CashReceiptKey != "") {
		QUrl url(*m_ServerUrl);
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
 * Prepare the invoice form for creating a new invoice.
 */
void SalesSection::prepareDocumentForm(QString dateTime, QString username)
{
	DocumentSection::prepareDocumentForm(dateTime, username);

	QWebFrame *frame = ui.webView->page()->mainFrame();
	QWebElement element;

	// Change div css style from disabled to enabled.
	element = frame->findFirstElement("#main_data");
	element.removeClass("disabled");
	element.addClass("enabled");

	element = frame->findFirstElement("#serial_number");
	element.setInnerXml("");

	element = frame->findFirstElement("#number");
	element.setInnerXml("");

	element = frame->findFirstElement("#nit_label");
	element.setInnerXml(element.toPlainText() + "*");

	element = frame->findFirstElement("#nit");
	element.setInnerXml("&nbsp;");

	element = frame->findFirstElement("#nit_label span");
	element.removeClass("hidden");

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
 * Extends functionality after the event.
 */
void SalesSection::createDocumentEvent(bool ok, QList<QMap<QString, QString>*> *list)
{
	if (ok) {
		setCustomer();
		m_BarCodeLineEdit->setFocus();
	}
}

/**
 * Extends functionality after the event.
 */
void SalesSection::cancelDocumentEvent(bool ok)
{
	if (ok) {
		QUrl url(*m_ServerUrl);
		url.addQueryItem("cmd", "print_cancelled_invoice");
		url.addQueryItem("key", m_DocumentKey);

		QString content = m_Request->get(url);

		QWebView webView;

		webView.setHtml(content);

		QPrinter printer;
		printer.setPrinterName(Registry::instance()->printerName());
		webView.print(&printer);
	}
}

/**
 * Auxiliary method for updating the QActions related to the recordset.
 */
QString SalesSection::navigateValues()
{
	if (m_Recordset.size() > 0) {
		if (m_Recordset.size() == 1) {
			return "110000011";
		} else if (m_Recordset.isFirst()) {
			return "110011111";
		} else if (m_Recordset.isLast()) {
			return "111100111";
		} else {
			return "111111111";
		}
	} else {
		return "000000001";
	}
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
 * Sets a discount to an invoice on the server.
 */
void SalesSection::setDiscountInvoice(QString discountKey)
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "set_discount_invoice");
	url.addQueryItem("discount_key", discountKey);
	url.addQueryItem("key", m_NewDocumentKey);
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	XmlTransformer *transformer = XmlTransformerFactory::instance()
				->create("stub");

	QString errorMsg;
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		fetchDocumentDetails(m_NewDocumentKey);
	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}

/**
 * Creates a cash receipt on the server.
 */
void SalesSection::showCashReceipt()
{
	bool errorFree = true;

	// If there is not a cash receipt already.
	if (m_CashReceiptKey == "") {
		QUrl url(*m_ServerUrl);
		url.addQueryItem("cmd", "create_cash_receipt");
		url.addQueryItem("invoice_key", m_NewDocumentKey);
		url.addQueryItem("type", "xml");

		QString content = m_Request->get(url);

		XmlTransformer *transformer = XmlTransformerFactory::instance()
				->create("object_key");

		QString errorMsg;
		if (m_Handler->handle(content,
				transformer, &errorMsg) == XmlResponseHandler::Success) {

			QList<QMap<QString, QString>*> list = transformer->content();
			QMap<QString, QString> *params = list[0];

			m_CashReceiptKey = params->value("key");
			m_Console->reset();

		} else {
			m_Console->displayError(errorMsg);
			errorFree = false;
		}

		delete transformer;
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
				m_NewDocumentKey, window);

		connect(section, SIGNAL(sessionStatusChanged(bool)), this,
				SIGNAL(sessionStatusChanged(bool)));
		connect(section, SIGNAL(cashReceiptSaved(QString)), this,
				SLOT(finishInvoice(QString)));

		window->setCentralWidget(section);
		window->show();

		section->loadUrl();
	}
}

/**
 * Fetchs the invoice printing format and prints it.
 */
void SalesSection::printInvoice(QString id)
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "print_invoice");
	url.addQueryItem("id", id);

	QString content = m_Request->get(url);

	QWebView webView;

	webView.setHtml(content);

	QPrinter printer;
	printer.setPrinterName(Registry::instance()->printerName());
	webView.print(&printer);
}
