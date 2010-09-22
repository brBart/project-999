/*
 * report_section.cpp
 *
 *  Created on: 20/09/2010
 *      Author: pc
 */

#include "report_section.h"

#include <QMenuBar>
#include <QPrinter>
#include "../registry.h"

/**
 * @class ReportSection
 * Section for displaying a report.
 */

/**
 * Constructs the section.
 */
ReportSection::ReportSection(QNetworkCookieJar *jar,
		QWebPluginFactory *factory, QUrl *reportUrl, QWidget *parent)
		: Section(jar, factory, reportUrl, parent)
{
	m_Window = dynamic_cast<QMainWindow*>(parentWidget());
	setActions();
	setMenu();

	ui.webView->load(*reportUrl);
}

/**
 * Sends the report to the printer.
 */
void ReportSection::printReport()
{
	QPrinter printer;
	printer.setPrinterName(Registry::instance()->printerName());
	ui.webView->print(&printer);
}

/**
 * Creates the QActions for the menu bar.
 */
void ReportSection::setActions()
{
	m_PrintAction = new QAction("Imprimir", this);
	m_PrintAction->setShortcut(tr("Ctrl+P"));
	connect(m_PrintAction, SIGNAL(triggered()), this, SLOT(printReport()));

	m_ExitAction = new QAction("Salir", this);
	m_ExitAction->setShortcut(Qt::Key_Escape);
	connect(m_ExitAction, SIGNAL(triggered()), m_Window, SLOT(close()));
}

/**
 * Sets the window's menu bar.
 */
void ReportSection::setMenu()
{
	QMenu *menu;

	menu = m_Window->menuBar()->addMenu("Archivo");
	menu->addAction(m_PrintAction);
	menu->addSeparator();
	menu->addAction(m_ExitAction);
}
