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
#include <QXmlQuery>
#include "../mainwindow.h"
#include "../console/console.h"
#include "../http_request/http_request.h"
#include "../xml_response_handler/xml_response_handler.h"
#include "../recordset/recordset.h"
#include "../actions_manager/actions_manager.h"
#include "../plugins/bar_code_line_edit.h"
#include "../authentication_dialog/authentication_dialog.h"
#include "../plugins/label.h"

class SalesSection: public Section
{
	Q_OBJECT

public:
	enum CRegisterStatus {Closed, Open, Error, Loading};
	enum DocumentStatus {Edit, Idle, Cancelled};
	SalesSection(QNetworkCookieJar *jar, QWebPluginFactory *factory,
			QUrl *serverUrl, QString cashRegisterKey, QWidget *parent = 0);
	virtual ~SalesSection();

public slots:
	void loadFinished(bool ok);
	void createInvoice();
	void updateCashRegisterStatus(QString content);
	void discardInvoice();
	void setCustomer();
	void fetchInvoice(QString id);
	void addProductInvoice(int quantity = 1);
	void deleteProductInvoice();
	void scrollUp();
	void scrollDown();
	void showAuthenticationDialog();
	void createDiscount();
	void createCashReceipt();
	void finishInvoice(QString Id);
	void unloadSection();
	void tempo();

private:
	Console *m_Console;
	HttpRequest *m_Request;
	XmlResponseHandler *m_Handler;
	Recordset m_Recordset;
	MainWindow *m_Window;
	ActionsManager m_ActionsManager;
	BarCodeLineEdit *m_BarCodeLineEdit;
	QXmlQuery *m_Query;
	QString m_StyleSheet;
	AuthenticationDialog *m_AuthenticationDlg;
	QString m_CashReceiptKey;
	Label *m_RecordsetLabel;

	QString m_CashRegisterKey;
	QString m_NewInvoiceKey;
	QString m_InvoiceKey;

	CRegisterStatus m_CashRegisterStatus;
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
	QAction *m_DeleteProductAction;
	QAction *m_SearchProductAction;

	// View actions.
	QAction *m_ScrollUpAction;
	QAction *m_ScrollDownAction;
	QAction *m_MoveFirstAction;
	QAction *m_MovePreviousAction;
	QAction *m_MoveNextAction;
	QAction *m_MoveLastAction;
	QAction *m_SearchAction;
	QAction *m_ConsultProductAction;

	void setActions();
	void setMenu();
	void setActionsManager();
	void fetchStyleSheet();
	void refreshRecordset();
	void fetchInvoiceForm();
	void updateActions();
	QString navigateValues();
	void prepareInvoiceForm(QString dateTime, QString username);
	void fetchCashRegisterStatus();
	void updateCustomerData(QString nit, QString name);
	void setPlugins();
	void fetchInvoiceDetails(QString invoiceKey);
	void setDiscountInvoice(QString discountKey);
	void printInvoice(QString id);
	void removeNewInvoiceFromSession();
	void removeInvoiceFromSession();
	void loadUrl(QUrl url);
};

#endif /* SALES_SECTION_H_ */
