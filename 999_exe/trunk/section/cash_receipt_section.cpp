/*
 * cash_receipt_section.cpp
 *
 *  Created on: 15/06/2010
 *      Author: pc
 */

#include "cash_receipt_section.h"

#include <QMenuBar>
#include <QInputDialog>
#include <QMessageBox>
#include "../console/console_factory.h"
#include "../xml_transformer/xml_transformer_factory.h"
#include "../registry.h"
#include "../voucher_dialog/voucher_dialog.h"
#include "../printer_status_handler/printer_status_handler.h"

/**
 * @class CashReceiptSection
 * Section for displaying a cash receipt.
 */

/**
 * Constructs the section.
 */
CashReceiptSection::CashReceiptSection(QNetworkCookieJar *jar,
		QWebPluginFactory *factory, QUrl *serverUrl, QString cashReceiptKey,
		QString invoiceKey, QWidget *parent) : Section(jar, factory, serverUrl,
				parent), m_CashReceiptKey(cashReceiptKey), m_InvoiceKey(invoiceKey)
{
	m_Window = dynamic_cast<QMainWindow*>(parentWidget());
	setActions();
	setMenu();

	m_Console = ConsoleFactory::instance()->createHtmlConsole();
	m_CashRequest = new HttpRequest(jar, this);
	m_Request = new HttpRequest(jar, this);
	m_Handler = new XmlResponseHandler(this);

	connect(m_Handler, SIGNAL(sessionStatusChanged(bool)), this,
			SIGNAL(sessionStatusChanged(bool)));
	connect(ui.webView, SIGNAL(loadFinished(bool)), this,
			SLOT(loadFinished(bool)));
	connect(ui.webView->page()->mainFrame(),
			SIGNAL(javaScriptWindowObjectCleared()), this, SLOT(addTimerObject()));
	connect(m_CashRequest, SIGNAL(finished(QString)), this,
			SLOT(updateChangeValue(QString)));
	connect(&m_CheckerTimer, SIGNAL(timeout()), this, SLOT(checkForChanges()));
	connect(&m_SenderTimer, SIGNAL(timeout()), this, SLOT(setCash()));

	m_Query = new QXmlQuery(QXmlQuery::XSLT20);

	fetchStyleSheet();

	m_CheckerTimer.setInterval(500);
	m_SenderTimer.setInterval(500);
	m_SenderTimer.setSingleShot(true);
}

/**
 * Destroys the console and query objects.
 */
CashReceiptSection::~CashReceiptSection()
{
	delete m_Console;
	delete m_Query;
}

/**
 * Sets the frame of the console.
 */
void CashReceiptSection::loadFinished(bool ok)
{
	Section::loadFinished(ok);
	m_Console->setFrame(ui.webView->page()->mainFrame());

	updateVouchers(fetchVouchersData());
}

/**
 * Sets the cash amount on the receipt on the server.
 */
void CashReceiptSection::setCash()
{
	if (!m_CashRequest->isBusy()) {
		QUrl url(*m_ServerUrl);
		url.addQueryItem("cmd", "set_cash_cash_receipt");
		url.addQueryItem("key", m_CashReceiptKey);
		url.addQueryItem("amount", m_CashValues.dequeue());
		url.addQueryItem("type", "xml");

		m_CashRequest->get(url, true);
	} else {
		// If there was already a waiting call, clean it.
		if (m_SenderTimer.timerId() > -1) {
			m_CashValues.dequeue();
			m_SenderTimer.stop();
		}

		m_SenderTimer.start();
	}
}

/**
 * Make available the timer object to the html page.
 */
void CashReceiptSection::addTimerObject()
{
	ui.webView->page()->mainFrame()
			->addToJavaScriptWindowObject("timerObj", &m_CheckerTimer);
}

/**
 * Loads the html page from the server.
 */
void CashReceiptSection::loadUrl()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "show_cash_receipt_form");
	url.addQueryItem("key", m_CashReceiptKey);

	ui.webView->load(url);
}

/**
 * Checks if the cash input value has changed.
 * If has change, the new value is sent to the server for setting the new cash
 * value.
 */
void CashReceiptSection::checkForChanges()
{
	QString cashValue = ui.webView->page()->mainFrame()
			->findFirstElement("#cash_input").evaluateJavaScript("this.value;")
			.toString();

	if (cashValue != m_CashValue) {
		m_CashValue = cashValue;
		m_CashValues.enqueue(cashValue);
		setCash();
	}
}

/**
 * Update the change value after setting the cash value on the server.
 */
void CashReceiptSection::updateChangeValue(QString content)
{
	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("change");

	QString errorMsg, elementId;
	XmlResponseHandler::ResponseType response =
			m_Handler->handle(content, transformer, &errorMsg, &elementId);
	if (response == XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->content();
		m_Console->cleanFailure("cash");
		ui.webView->page()->mainFrame()
				->findFirstElement("#change_amount")
				.setInnerXml(list[0]->value("change"));
	} else if (response == XmlResponseHandler::Failure) {
		m_Console->cleanFailure("cash");
		m_Console->displayFailure(errorMsg, "cash");
	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}

/**
 * Shows the voucher dialog for adding a voucher to the cash receipt on the server.
 */
void CashReceiptSection::showVoucherDialog()
{
	VoucherDialog dialog(ui.webView->page()->networkAccessManager()->cookieJar(),
			m_ServerUrl, m_CashReceiptKey, m_InvoiceKey, this, Qt::WindowTitleHint);

	connect(&dialog, SIGNAL(sessionStatusChanged(bool)), this,
			SIGNAL(sessionStatusChanged(bool)), Qt::QueuedConnection);

	dialog.init();
	dialog.move(x() + 100, y() + 100);

	if (dialog.exec() == QDialog::Accepted) {
		QString content = fetchVouchersData();
		updateVouchers(content);
		updateVouchersTotal(content);
	}
}

/**
 * Removes a voucher from a cash receipt on the server.
 */
void CashReceiptSection::deleteVoucherCashReceipt()
{
	bool ok;
	int row = QInputDialog::getInt(this, "Quitar Voucher", "Fila #:", 0, 1, 9999,
			1, &ok, Qt::WindowTitleHint);

	if (ok) {
		QWebElement tr = ui.webView->page()->mainFrame()
				->findFirstElement("#tr" + QString::number(row));

		if (!tr.isNull()) {
			QWebElement td = tr.findFirst("td");
			QString detailId = td.attribute("id");

			QUrl url(*m_ServerUrl);
			url.addQueryItem("cmd", "delete_voucher_cash_receipt");
			url.addQueryItem("key", m_CashReceiptKey);
			url.addQueryItem("detail_id", detailId);
			url.addQueryItem("type", "xml");

			QString content = m_Request->get(url);

			XmlTransformer *transformer = XmlTransformerFactory::instance()
					->create("stub");

			QString errorMsg;
			XmlResponseHandler::ResponseType response =
					m_Handler->handle(content, transformer, &errorMsg);
			if (response == XmlResponseHandler::Success) {
				QString data = fetchVouchersData();
				updateVouchers(data);
				updateVouchersTotal(data);
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
void CashReceiptSection::scrollUp()
{
	QWebElement div = ui.webView->page()->mainFrame()->findFirstElement("#details");

	if (div.evaluateJavaScript("this.scrollTop").toInt() > 0)
		div.evaluateJavaScript("this.scrollTop -= 10");
}

/**
 * Scrolls the detail's div element down.
 */
void CashReceiptSection::scrollDown()
{
	QWebElement div = ui.webView->page()->mainFrame()->findFirstElement("#details");

	if (div.evaluateJavaScript("this.scrollTop").toInt()
			< div.evaluateJavaScript("this.scrollHeight").toInt())
		div.evaluateJavaScript("this.scrollTop += 10");
}

/**
 * Saves the cash receipt in the server.
 */
void CashReceiptSection::saveCashReceipt()
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

	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "save_object");
	url.addQueryItem("key", m_CashReceiptKey);
	url.addQueryItem("type", "xml");

	QString content = m_Request->get(url);

	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("object_id");

	QString errorMsg, elementId;
	XmlResponseHandler::ResponseType response = m_Handler->handle(content,
			transformer, &errorMsg, &elementId);
	if (response == XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->content();
		QMap<QString, QString> *objectId = list[0];

		emit cashReceiptSaved(objectId->value("id"));

		m_Window->close();
	} else if (response == XmlResponseHandler::Failure) {
		m_Console->reset();
		m_Console->displayFailure(errorMsg, elementId);
	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}

/**
 * Creates the QActions for the menu bar.
 */
void CashReceiptSection::setActions()
{
	m_SaveAction = new QAction("Guardar", this);
	m_SaveAction->setShortcut(tr("Ctrl+S"));
	connect(m_SaveAction, SIGNAL(triggered()), this, SLOT(saveCashReceipt()));

	m_ExitAction = new QAction("Salir", this);
	m_ExitAction->setShortcut(Qt::Key_Escape);
	connect(m_ExitAction, SIGNAL(triggered()), m_Window, SLOT(close()));

	m_AddVoucherAction = new QAction("Agregar voucher", this);
	m_AddVoucherAction->setShortcut(tr("Ctrl+I"));
	connect(m_AddVoucherAction, SIGNAL(triggered()), this,
			SLOT(showVoucherDialog()));

	m_DeleteVoucherAction = new QAction("Quitar voucher", this);
	m_DeleteVoucherAction->setShortcut(tr("Ctrl+D"));
	connect(m_DeleteVoucherAction, SIGNAL(triggered()), this,
			SLOT(deleteVoucherCashReceipt()));

	m_ScrollUpAction = new QAction("Desplazar arriba", this);
	m_ScrollUpAction->setShortcut(tr("Ctrl+Up"));
	connect(m_ScrollUpAction, SIGNAL(triggered()), this, SLOT(scrollUp()));

	m_ScrollDownAction = new QAction("Desplazar abajo", this);
	m_ScrollDownAction->setShortcut(tr("Ctrl+Down"));
	connect(m_ScrollDownAction, SIGNAL(triggered()), this, SLOT(scrollDown()));
}

/**
 * Sets the window's menu bar.
 */
void CashReceiptSection::setMenu()
{
	QMenu *menu;

	menu = m_Window->menuBar()->addMenu("Archivo");
	menu->addAction(m_SaveAction);
	menu->addAction(m_ExitAction);

	menu = m_Window->menuBar()->addMenu("Editar");
	menu->addAction(m_AddVoucherAction);
	menu->addAction(m_DeleteVoucherAction);

	menu = m_Window->menuBar()->addMenu("Ver");
	menu->addAction(m_ScrollUpAction);
	menu->addAction(m_ScrollDownAction);
}

/**
 * Fetch the xslt style sheet from the server.
 */
void CashReceiptSection::fetchStyleSheet()
{
	QUrl url = *(Registry::instance()->xslUrl());
	url.setPath(url.path() + "cash_receipt_vouchers.xsl");

	m_StyleSheet = m_Request->get(url);
}

/**
 * Fetch the vouchers from the server.
 */
QString CashReceiptSection::fetchVouchersData()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "get_cash_receipt_vouchers");
	url.addQueryItem("key", m_CashReceiptKey);
	url.addQueryItem("type", "xml");

	return m_Request->get(url);
}

/**
 * Updates the vouchers' div table with new data.
 */
void CashReceiptSection::updateVouchers(QString content)
{
	m_Query->setFocus(content);
	m_Query->setQuery(m_StyleSheet);

	QString result;
	m_Query->evaluateTo(&result);

	QWebElement div = ui.webView->page()->mainFrame()->findFirstElement("#details");
	div.setInnerXml(result);
	div.evaluateJavaScript("this.scrollTop = this.scrollHeight;");
}

/**
 * Updates the total html span element with a new value.
 */
void CashReceiptSection::updateVouchersTotal(QString content)
{
	XmlTransformer *transformer = XmlTransformerFactory::instance()
				->create("total");

	QString errorMsg;
	if (m_Handler->handle(content, transformer, &errorMsg) ==
			XmlResponseHandler::Success) {
		QList<QMap<QString, QString>*> list = transformer->content();
		QMap<QString, QString> *total = list[0];

		QWebElement element =
				ui.webView->page()->mainFrame()->findFirstElement("#vouchers_total");
		element.setInnerXml(total->value("total"));
	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}
