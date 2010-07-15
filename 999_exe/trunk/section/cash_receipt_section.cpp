/*
 * cash_receipt_section.cpp
 *
 *  Created on: 15/06/2010
 *      Author: pc
 */

#include "cash_receipt_section.h"

#include <QMenuBar>
#include "../console/console_factory.h"
#include "../xml_transformer/xml_transformer_factory.h"
#include "../registry.h"
#include "../voucher_dialog/voucher_dialog.h"

/**
 * @class CashReceiptSection
 * Section for displaying a cash receipt.
 */

/**
 * Constructs the section.
 */
CashReceiptSection::CashReceiptSection(QNetworkCookieJar *jar,
		QWebPluginFactory *factory, QUrl *serverUrl, QString cashReceiptKey,
		QWidget *parent) : Section(jar, factory, serverUrl, parent),
		m_CashReceiptKey(cashReceiptKey)
{
	m_Window = dynamic_cast<QMainWindow*>(parentWidget());
	setActions();
	setMenu();

	m_Console = ConsoleFactory::instance()->createHtmlConsole();
	m_CashRequest = new HttpRequest(jar, this);
	m_Request = new HttpRequest(jar, this);
	m_Handler = new XmlResponseHandler(this);

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
 * Destroys the console object.
 */
CashReceiptSection::~CashReceiptSection()
{
	delete m_Console;
}

/**
 * Sets the frame of the console.
 */
void CashReceiptSection::loadFinished(bool ok)
{
	Section::loadFinished(ok);
	m_Console->setFrame(ui.webView->page()->mainFrame());

	fetchVouchers();
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
			m_ServerUrl, this, Qt::WindowTitleHint);

	connect(&dialog, SIGNAL(sessionStatusChanged(bool)), this,
			SIGNAL(sessionStatusChanged(bool)), Qt::QueuedConnection);

	dialog.init();
	dialog.exec();
}

/**
 * Creates the QActions for the menu bar.
 */
void CashReceiptSection::setActions()
{
	m_SaveAction = new QAction("Guardar", this);
	m_SaveAction->setShortcut(tr("Ctrl+S"));
	//connect(m_SaveAction, SIGNAL(triggered()), this, SLOT(createCashReceipt()));

	m_ExitAction = new QAction("Salir", this);
	m_ExitAction->setShortcut(tr("Ctrl+Q"));
	connect(m_ExitAction, SIGNAL(triggered()), m_Window, SLOT(close()));

	m_AddVoucherAction = new QAction("Agregar voucher", this);
	m_AddVoucherAction->setShortcut(tr("Ctrl+I"));
	connect(m_AddVoucherAction, SIGNAL(triggered()), this,
			SLOT(showVoucherDialog()));

	m_DeleteVoucherAction = new QAction("Quitar voucher", this);
	m_DeleteVoucherAction->setShortcut(tr("Ctrl+D"));
	/*connect(m_DeleteVoucherAction, SIGNAL(triggered()), this,
			SLOT(deleteVoucherInvoice()));*/

	m_ScrollUpAction = new QAction("Desplazar arriba", this);
	m_ScrollUpAction->setShortcut(tr("Ctrl+Up"));
	//connect(m_ScrollUpAction, SIGNAL(triggered()), this, SLOT(scrollUp()));

	m_ScrollDownAction = new QAction("Desplazar abajo", this);
	m_ScrollDownAction->setShortcut(tr("Ctrl+Down"));
	//connect(m_ScrollDownAction, SIGNAL(triggered()), this, SLOT(scrollDown()));
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
void CashReceiptSection::fetchVouchers()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "get_cash_receipt_vouchers");
	url.addQueryItem("key", m_CashReceiptKey);
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
