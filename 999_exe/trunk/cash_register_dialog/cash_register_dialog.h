#ifndef CASH_REGISTER_DIALOG_H
#define CASH_REGISTER_DIALOG_H

#include <QtGui/QDialog>
#include "ui_cash_register_dialog.h"

#include <QNetworkAccessManager>
#include <QUrl>
#include "../console/console.h"
#include "../http_request/http_request.h"
#include "../xml_response_handler/xml_response_handler.h"

class CashRegisterDialog : public QDialog
{
    Q_OBJECT

public:
    CashRegisterDialog(QNetworkAccessManager *manager, QUrl *url,
    		QWidget *parent = 0);
    ~CashRegisterDialog();
    void init();

signals:
	void sessionStatusChanged(bool isActive);

private:
    Ui::CashRegisterDialogClass ui;
    QUrl *m_ServerUrl;
    Console *m_Console;
    HttpRequest *m_Request;
    XmlResponseHandler *m_Handler;
};

#endif // CASH_REGISTER_DIALOG_H
