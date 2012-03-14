/*
 * object_section.h
 *
 *  Created on: 22/09/2010
 *      Author: pc
 */

#ifndef OBJECT_SECTION_H_
#define OBJECT_SECTION_H_

#include "section.h"

#include <QAction>
#include "../main_window.h"
#include "../console/console.h"
#include "../http_request/http_request.h"
#include "../xml_response_handler/xml_response_handler.h"
#include "../actions_manager/actions_manager.h"

class ObjectSection: public Section
{
	Q_OBJECT

public:
	enum ObjectStatus {Closed, Open, Error, Loading};
	ObjectSection(QNetworkCookieJar *jar, QWebPluginFactory *factory,
			QUrl *serverUrl, QWidget *parent = 0);
	virtual ~ObjectSection();
	void init();
	void setPreliminaryReportName(QString name);
	void setReportName(QString name);
	void setObjectName(QString name);
	void setCloseMessage(QString msg);

public slots:
	void loadFinished(bool ok);
	void viewReport(int action);
	void closeObject();

protected:
	QString m_PreliminaryReportName;
	QString m_ReportName;
	QString m_ObjectName;
	QString m_CloseMessage;

	virtual QUrl closeObjectUrl() = 0;
	virtual QUrl reportUrl(bool isPreliminary) = 0;
	virtual QUrl formUrl() = 0;

private:
	Console *m_Console;
	HttpRequest *m_Request;
	XmlResponseHandler *m_Handler;
	MainWindow *m_Window;
	ActionsManager m_ActionsManager;

	ObjectStatus m_ObjectStatus;

	// File actions.
	QAction *m_ExitAction;

	// Edit actions.
	QAction *m_CloseAction;

	// View actions.
	QAction *m_ViewPreliminaryReportAction;
	QAction *m_ViewReportAction;

	void setActions();
	void setMenu();
	void setActionsManager();
	void fetchForm();
	void updateActions();
};

#endif /* OBJECT_SECTION_H_ */
