#ifndef AVAILABLE_CASH_DIALOG_H
#define AVAILABLE_CASH_DIALOG_H

#include <QtGui/QDialog>
#include "ui_available_cash_dialog.h"

#include <QNetworkCookieJar>
#include <QUrl>
#include "../console/console.h"
#include "../http_request/http_request.h"
#include "../xml_response_handler/xml_response_handler.h"

class AvailableCashDialog : public QDialog
{
    Q_OBJECT

public:
    AvailableCashDialog(QNetworkCookieJar *jar, QUrl *url, QWidget *parent = 0,
    		Qt::WindowFlags f = 0);
    ~AvailableCashDialog();
    void init();

signals:
	void sessionStatusChanged(bool isActive);

private:
    Ui::AvailableCashDialogClass ui;
    QUrl *m_ServerUrl;
	Console *m_Console;
	HttpRequest *m_Request;
	XmlResponseHandler *m_Handler;

	void setConsole();
};

#endif // AVAILABLE_CASH_DIALOG_H
