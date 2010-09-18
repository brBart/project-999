/*
 * cash_register_section.cpp
 *
 *  Created on: 18/09/2010
 *      Author: pc
 */

#include "cash_register_section.h"

#include "../console/console_factory.h"

/**
 * @class CashRegisterSection
 * Section for controlling the cash register actions.
 */

/**
 * Constructs the section.
 */
CashRegisterSection::CashRegisterSection(QNetworkCookieJar *jar,
		QWebPluginFactory *factory, QUrl *serverUrl, QString cashRegisterKey,
		QWidget *parent) : Section(jar, factory, serverUrl, parent),
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

	setActions();
	setMenu();
	setActionsManager();

	fetchForm();
}

/**
 * Destroys the console object.
 */
CashRegisterSection::~CashRegisterSection()
{
	delete m_Console;
}

/**
 * Updates the status of the section depending on the page received.
 */
void CashRegisterSection::loadFinished(bool ok)
{
	Section::loadFinished(ok);

	if (ok) {
		QWebFrame *frame = ui.webView->page()->mainFrame();
		m_CashRegisterStatus =
				CashRegisterStatus(frame->evaluateJavaScript("cashRegisterStatus")
						.toInt());
	} else {
		m_CashRegisterStatus = Error;
	}

	m_Console->setFrame(ui.webView->page()->mainFrame());

	//updateActions();
}

/**
 * Creates the QActions for the menu bar.
 */
void CashRegisterSection::setActions()
{
	m_ExitAction = new QAction("Salir", this);
	m_ExitAction->setShortcut(Qt::Key_Escape);
	connect(m_ExitAction, SIGNAL(triggered()), m_Window, SLOT(loadMainSection()));

	m_CloseAction = new QAction("Cerrar", this);
	m_CloseAction->setShortcut(tr("Ctrl+C"));

	m_ViewPreliminarySalesReportAction = new QAction("Corte de caja preliminar",
			this);
	m_ViewPreliminarySalesReportAction->setShortcut(tr("Ctrl+P"));

	m_ViewSalesReportAction = new QAction("Corte de caja", this);
	m_ViewSalesReportAction->setShortcut(tr("Ctrl+A"));
}

/**
 * Sets the window's menu bar.
 */
void CashRegisterSection::setMenu()
{
	QMenu *menu;

	menu = m_Window->menuBar()->addMenu("Archivo");
	menu->addAction(m_ExitAction);

	menu = m_Window->menuBar()->addMenu("Editar");
	menu->addAction(m_CloseAction);

	menu = m_Window->menuBar()->addMenu("Ver");
	menu->addAction(m_ViewPreliminarySalesReportAction);
	menu->addAction(m_ViewSalesReportAction);
}

/**
 * Sets the ActionsManager with the already created QActions.
 */
void CashRegisterSection::setActionsManager()
{
	QList<QAction*> *actions = new QList<QAction*>();

	*actions << m_ExitAction;

	*actions << m_CloseAction;

	*actions << m_ViewPreliminarySalesReportAction;
	*actions << m_ViewSalesReportAction;

	m_ActionsManager.setActions(actions);
}

/**
 * Fetchs the cash register form from the server.
 */
void CashRegisterSection::fetchForm()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "show_cash_register_form");

	ui.webView->load(url);
}
