/*
 * object_section.cpp
 *
 *  Created on: 22/09/2010
 *      Author: pc
 */

#include "object_section.h"

#include <QSignalMapper>
#include <QMainWindow>
#include <QMessageBox>
#include "../console/console_factory.h"
#include "report_section.h"
#include "../xml_transformer/xml_transformer_factory.h"

/**
 * @class ObjectSection
 * Defines common functionality for the object section derived classes.
 */

/**
 * Constructs the section.
 */
ObjectSection::ObjectSection(QNetworkCookieJar *jar,
		QWebPluginFactory *factory, QUrl *serverUrl, QWidget *parent)
		: Section(jar, factory, serverUrl, parent)
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
}

/**
 * Destroys the console object.
 */
ObjectSection::~ObjectSection()
{
	delete m_Console;
}

/**
 * Initiates the section.
 */
void ObjectSection::init()
{
	setActions();
	setMenu();
	setActionsManager();

	fetchForm();
}

/**
 * Sets the name of the preliminary report.
 */
void ObjectSection::setPreliminaryReportName(QString name)
{
	m_PreliminaryReportName = name;
}

/**
 * Sets the report's name.
 */
void ObjectSection::setReportName(QString name)
{
	m_ReportName = name;
}

/**
 * Sets the object's name.
 */
void ObjectSection::setObjectName(QString name)
{
	m_ObjectName = name;
}

/**
 * Sets the message to display when closing the object.
 */
void ObjectSection::setCloseMessage(QString msg)
{
	m_CloseMessage = msg;
}

/**
 * Updates the status of the section depending on the page received.
 */
void ObjectSection::loadFinished(bool ok)
{
	Section::loadFinished(ok);

	if (ok) {
		QWebFrame *frame = ui.webView->page()->mainFrame();
		m_ObjectStatus =
				ObjectStatus(frame->evaluateJavaScript("objectStatus")
						.toInt());
	} else {
		m_ObjectStatus = Error;
	}

	m_Console->setFrame(ui.webView->page()->mainFrame());

	updateActions();
}

/**
 * Displays the report section.
 */
void ObjectSection::viewReport(int action)
{
	QMainWindow *window = new QMainWindow(this, Qt::WindowTitleHint);
	window->setAttribute(Qt::WA_DeleteOnClose);
	window->setWindowModality(Qt::WindowModal);
	window->setWindowTitle(action ? "**PRELIMINAR**" : m_ReportName);
	window->resize(width() - (width() / 3), height() - 150);
	window->move(x() + (width() / 6), y() + 100);

	QUrl url = reportUrl(action);

	ReportSection *section = new ReportSection(
			ui.webView->page()->networkAccessManager()->cookieJar(),
			ui.webView->page()->pluginFactory(), &url, window);

	connect(section, SIGNAL(sessionStatusChanged(bool)), this,
			SIGNAL(sessionStatusChanged(bool)));

	window->setCentralWidget(section);
	window->show();
}

/**
 * Closes the cash register on the server.
 */
void ObjectSection::closeObject()
{
	if (QMessageBox::question(this, "Cerrar " + m_ObjectName,
			m_CloseMessage + " ¿Desea cerrar?",
			QMessageBox::Yes | QMessageBox::No) == QMessageBox::No)
		return;

	QString content = m_Request->get(closeObjectUrl());

	XmlTransformer *transformer = XmlTransformerFactory::instance()
			->create("stub");

	QString errorMsg;
	if (m_Handler->handle(content, transformer, &errorMsg)
			== XmlResponseHandler::Success) {

		m_Console->reset();

		QWebElement element = ui.webView->page()->mainFrame()
				->findFirstElement("#object_status");
		element.setInnerXml("Cerrado");
		element.removeClass("pos_open_status");
		element.addClass("pos_closed_status");

		m_ObjectStatus = Closed;

		updateActions();

	} else {
		m_Console->displayError(errorMsg);
	}

	delete transformer;
}

/**
 * Creates the QActions for the menu bar.
 */
void ObjectSection::setActions()
{
	m_ExitAction = new QAction("Salir", this);
	m_ExitAction->setShortcut(Qt::Key_Escape);
	connect(m_ExitAction, SIGNAL(triggered()), m_Window, SLOT(loadMainSection()));

	m_CloseAction = new QAction("Cerrar", this);
	m_CloseAction->setShortcut(tr("Ctrl+C"));
	connect(m_CloseAction, SIGNAL(triggered()), this, SLOT(closeObject()));

	QSignalMapper *mapper = new QSignalMapper(this);

	m_ViewPreliminaryReportAction = new QAction(m_PreliminaryReportName, this);
	m_ViewPreliminaryReportAction->setShortcut(tr("Ctrl+I"));
	connect(m_ViewPreliminaryReportAction, SIGNAL(triggered()), mapper,
			SLOT(map()));

	m_ViewReportAction = new QAction(m_ReportName, this);
	m_ViewReportAction->setShortcut(tr("Ctrl+A"));
	connect(m_ViewReportAction, SIGNAL(triggered()), mapper, SLOT(map()));

	mapper->setMapping(m_ViewReportAction, 0);
	mapper->setMapping(m_ViewPreliminaryReportAction, 1);

	connect(mapper, SIGNAL(mapped(int)), this, SLOT(viewReport(int)));
}

/**
 * Sets the window's menu bar.
 */
void ObjectSection::setMenu()
{
	QMenu *menu;

	menu = m_Window->menuBar()->addMenu("Archivo");
	menu->addAction(m_ExitAction);

	menu = m_Window->menuBar()->addMenu("Editar");
	menu->addAction(m_CloseAction);

	menu = m_Window->menuBar()->addMenu("Ver");
	menu->addAction(m_ViewPreliminaryReportAction);
	menu->addAction(m_ViewReportAction);
}

/**
 * Sets the ActionsManager with the already created QActions.
 */
void ObjectSection::setActionsManager()
{
	QList<QAction*> *actions = new QList<QAction*>();

	*actions << m_ExitAction;

	*actions << m_CloseAction;

	*actions << m_ViewPreliminaryReportAction;
	*actions << m_ViewReportAction;

	m_ActionsManager.setActions(actions);
}

/**
 * Fetchs the cash register form from the server.
 */
void ObjectSection::fetchForm()
{
	m_ObjectStatus = Loading;
	updateActions();

	ui.webView->load(formUrl());
}

/**
 * Updates the QActions depending on the actual section status.
 */
void ObjectSection::updateActions()
{
	QString values;

	switch (m_ObjectStatus) {
		case Open:
			values = "1110";
			break;

		case Closed:
			values = "1001";
			break;

		case Error:
			values = "1000";
			break;

		case Loading:
			values = "0000";
			break;

		default:;
	}

	m_ActionsManager.updateActions(values);
}
