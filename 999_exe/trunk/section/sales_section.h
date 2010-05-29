/*
 * sales_section.h
 *
 *  Created on: 08/05/2010
 *      Author: pc
 */

#ifndef SALES_SECTION_H_
#define SALES_SECTION_H_

#include "section.h"

#include <QAction>
#include "../mainwindow.h"
#include "../console/console.h"
#include "../http_request/http_request.h"
#include "../xml_response_handler/xml_response_handler.h"
#include "../recordset/recordset.h"
#include "../actions_manager/actions_manager.h"

class SalesSection: public Section
{
	Q_OBJECT

public:
	enum CRegisterStatus {Closed, Open, Error};
	enum DocumentStatus {Edit, Idle, Cancelled};
	SalesSection(QNetworkCookieJar *jar, QWebPluginFactory *factory,
			QUrl *serverUrl, QString cRegisterKey, QWidget *parent = 0);
	virtual ~SalesSection();

public slots:
	void loadFinished(bool ok);
	void createInvoice();
	void updateCashRegisterStatus(QString content);
	void discardInvoice();
	void setCustomer();
	void fetchInvoice(QString id);

private:
	Console *m_Console;
	HttpRequest *m_Request;
	XmlResponseHandler *m_Handler;
	Recordset m_Recordset;
	MainWindow *m_Window;
	ActionsManager m_ActionsManager;

	QString m_CRegisterKey;
	QString m_NewInvoiceKey;

	CRegisterStatus m_CRegisterStatus;
	DocumentStatus m_DocumentStatus;

	// File actions.
	QAction *m_NewAction;
	QAction *m_SaveAction;
	QAction *m_DiscardAction;
	QAction *m_CancelAction;
	QAction *m_ExitAction;

	// Edit actions.
	QAction *m_ClientAction;
	QAction *m_DiscountAction;
	QAction *m_AddProductAction;
	QAction *m_RemoveProductAction;
	QAction *m_SearchProductAction;

	// View actions.
	QAction *m_MoveFirstAction;
	QAction *m_MovePreviousAction;
	QAction *m_MoveNextAction;
	QAction *m_MoveLastAction;
	QAction *m_SearchAction;
	QAction *m_ConsultProductAction;

	void setActions();
	void setMenu();
	void setActionsManager();
	void setPlugins();
	void refreshRecordset();
	void fetchInvoiceForm();
	void updateActions();
	QString viewValues();
	void prepareInvoiceForm(QString dateTime, QString username);
	void fetchCashRegisterStatus();
	void updateCustomerData(QString nit, QString name);
};

#endif /* SALES_SECTION_H_ */
