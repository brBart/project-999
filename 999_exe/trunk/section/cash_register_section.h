/*
 * cash_register_section.h
 *
 *  Created on: 18/09/2010
 *      Author: pc
 */

#ifndef CASH_REGISTER_SECTION_H_
#define CASH_REGISTER_SECTION_H_

#include "section.h"

#include <QAction>
#include "../main_window.h"
#include "../console/console.h"
#include "../http_request/http_request.h"
#include "../xml_response_handler/xml_response_handler.h"
#include "../actions_manager/actions_manager.h"

class CashRegisterSection: public Section
{
	Q_OBJECT

public:
	enum CashRegisterStatus {Closed, Open, Error, Loading};
	CashRegisterSection(QNetworkCookieJar *jar, QWebPluginFactory *factory,
			QUrl *serverUrl, QString cashRegisterKey, QWidget *parent = 0);
	virtual ~CashRegisterSection();

public slots:
	void loadFinished(bool ok);
	void viewReport(int action);

private:
	Console *m_Console;
	HttpRequest *m_Request;
	XmlResponseHandler *m_Handler;
	MainWindow *m_Window;
	ActionsManager m_ActionsManager;
	QString m_CashRegisterKey;

	CashRegisterStatus m_CashRegisterStatus;

	// File actions.
	QAction *m_ExitAction;

	// Edit actions.
	QAction *m_CloseAction;

	// View actions.
	QAction *m_ViewPreliminarySalesReportAction;
	QAction *m_ViewSalesReportAction;

	void setActions();
	void setMenu();
	void setActionsManager();
	void fetchForm();
	void updateActions();
};

#endif /* CASH_REGISTER_SECTION_H_ */
