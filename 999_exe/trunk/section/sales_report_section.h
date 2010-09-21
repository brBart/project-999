/*
 * sales_report_section.h
 *
 *  Created on: 20/09/2010
 *      Author: pc
 */

#ifndef SALES_REPORT_SECTION_H_
#define SALES_REPORT_SECTION_H_

#include "section.h"

#include <QMainWindow>

class SalesReportSection: public Section
{
	Q_OBJECT

public:
	SalesReportSection(QNetworkCookieJar *jar, QWebPluginFactory *factory,
			QUrl *serverUrl, QString cashRegisterKey, QWidget *parent = 0);
	virtual ~SalesReportSection() {};

private:
	QString m_CashRegisterKey;
	QMainWindow *m_Window;

	// File actions.
	QAction *m_PrintAction;
	QAction *m_ExitAction;

	void setActions();
	void setMenu();
};

#endif /* SALES_REPORT_SECTION_H_ */
