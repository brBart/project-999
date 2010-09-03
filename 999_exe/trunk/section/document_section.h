/*
 * document_section.h
 *
 *  Created on: 26/08/2010
 *      Author: pc
 */

#ifndef DOCUMENT_SECTION_H_
#define DOCUMENT_SECTION_H_

#include "section.h"

#include <QAction>
#include <QXmlQuery>
#include "../main_window.h"
#include "../console/console.h"
#include "../http_request/http_request.h"
#include "../xml_response_handler/xml_response_handler.h"
#include "../recordset/recordset.h"
#include "../actions_manager/actions_manager.h"
#include "../authentication_dialog/authentication_dialog.h"
#include "../plugins/label.h"

class DocumentSection: public Section
{
	Q_OBJECT

public:
	enum CashRegisterStatus {Closed, Open, Error, Loading};
	enum DocumentStatus {Edit, Idle, Cancelled};
	DocumentSection(QNetworkCookieJar *jar, QWebPluginFactory *factory,
			QUrl *serverUrl, QString cashRegisterKey, QWidget *parent = 0);
	virtual ~DocumentSection();
	void init();

	void setStyleSheetFileName(QString name);
	void setGetDocumentDetailsCmd(QString cmd);
	void setGetDocumentListCmd(QString cmd);
	void setShowDocumentFormCmd(QString cmd);
	void setGetDocumentCmd(QString cmd);
	void setCreateDocumentCmd(QString cmd);
	void setDeleteItemDocumentCmd(QString cmd);
	void setCanceDocumentCmd(QString cmd);

	void setCreateDocumentTransformerName(QString name);

public slots:
	void loadFinished(bool ok);
	void fetchDocument(QString id);
	void unloadSection();
	void createDocument();
	void updateCashRegisterStatus(QString content);
	void discardDocument();
	void scrollUp();
	void scrollDown();
	void showAuthenticationDialogForCancel();
	void cancelDocument();

protected:
	Console *m_Console;
	HttpRequest *m_Request;
	XmlResponseHandler *m_Handler;
	Recordset m_Recordset;
	MainWindow *m_Window;
	ActionsManager m_ActionsManager;
	AuthenticationDialog *m_AuthenticationDlg;
	Label *m_RecordsetLabel;

	QXmlQuery *m_Query;
	QString m_StyleSheet;

	QString m_NewDocumentKey;
	QString m_DocumentKey;
	QString m_CashRegisterKey;

	CashRegisterStatus m_CashRegisterStatus;
	DocumentStatus m_DocumentStatus;

	// File actions.
	QAction *m_NewAction;
	QAction *m_SaveAction;
	QAction *m_DiscardAction;
	QAction *m_CancelAction;
	QAction *m_ExitAction;

	// Edit actions.
	QAction *m_AddItemAction;
	QAction *m_DeleteItemAction;

	// View actions.
	QAction *m_ScrollUpAction;
	QAction *m_ScrollDownAction;
	QAction *m_MoveFirstAction;
	QAction *m_MovePreviousAction;
	QAction *m_MoveNextAction;
	QAction *m_MoveLastAction;
	QAction *m_SearchAction;

	void loadUrl(QUrl url);
	void refreshRecordset();
	void fetchDocumentDetails(QString documentKey);
	void fetchDocumentForm();
	void removeNewDocumentFromSession();
	virtual void prepareDocumentForm(QString dateTime, QString username);
	void deleteItemDocument(int row);
	WebPluginFactory* webPluginFactory();
	virtual void setPlugins();

	virtual void createDocumentEvent(bool ok,
			QList<QMap<QString, QString>*> *list = 0);

	virtual void setActions() = 0;
	virtual void setMenu() = 0;
	virtual void setActionsManager() = 0;
	virtual void installRecordsetSearcher() = 0;
	virtual void updateActions() = 0;

private:
	QString m_StyleSheetFileName;
	QString m_GetDocumentDetailsCmd;
	QString m_GetDocumentListCmd;
	QString m_ShowDocumentFormCmd;
	QString m_GetDocumentCmd;
	QString m_CreateDocumentCmd;
	QString m_DeleteItemDocumentCmd;
	QString m_CancelDocumentCmd;

	QString m_CreateDocumentTransformer;

	void fetchStyleSheet();
	void removeDocumentFromSession();
	void fetchCashRegisterStatus();
};

#endif /* DOCUMENT_SECTION_H_ */
