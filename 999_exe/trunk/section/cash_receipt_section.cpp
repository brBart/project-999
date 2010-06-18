/*
 * cash_receipt_section.cpp
 *
 *  Created on: 15/06/2010
 *      Author: pc
 */

#include "cash_receipt_section.h"

#include <QMenuBar>
#include "../console/console_factory.h"
#include "../plugins/cash_line_edit.h"
#include "../plugins/web_plugin_factory.h"

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
	ui.webView->setFocusPolicy(Qt::NoFocus);
	setActions();
	setMenu();
	setPlugins();

	m_Console = ConsoleFactory::instance()->createHtmlConsole();

	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "show_cash_receipt_form");
	url.addQueryItem("key", m_CashReceiptKey);

	ui.webView->load(url);
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
}

/**
 * Sets the cash amount on the receipt on the server.
 */
void CashReceiptSection::setCash(QString amount)
{

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
 * Installs the necessary plugins widgets in the plugin factory of the web view.
 */
void CashReceiptSection::setPlugins()
{
	CashLineEdit *cashLineEdit = new CashLineEdit();

	WebPluginFactory *factory =
			static_cast<WebPluginFactory*>(ui.webView->page()->pluginFactory());
	factory->install("application/x-cash_line_edit", cashLineEdit);

	connect(cashLineEdit, SIGNAL(textEdited(QString)), this,
			SLOT(setCash(QString)));
}
