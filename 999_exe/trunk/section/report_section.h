/*
 * sales_report_section.h
 *
 *  Created on: 20/09/2010
 *      Author: pc
 */

#ifndef REPORT_SECTION_H_
#define REPORT_SECTION_H_

#include "section.h"

#include <QMainWindow>

class ReportSection: public Section
{
	Q_OBJECT

public:
	ReportSection(QNetworkCookieJar *jar, QWebPluginFactory *factory,
			QUrl *reportUrl, QWidget *parent = 0);
	virtual ~ReportSection() {};

public slots:
	void printReportWithOptions();
	void printReport();

private:
	QMainWindow *m_Window;

	// File actions.
	QAction *m_PrintOptionsAction;
	QAction *m_PrintAction;
	QAction *m_ExitAction;

	void setActions();
	void setMenu();
};

#endif /* SALES_REPORT_SECTION_H_ */
