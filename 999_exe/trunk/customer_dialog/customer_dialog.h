#ifndef CUSTOMER_DIALOG_H
#define CUSTOMER_DIALOG_H

#include <QtGui/QDialog>
#include "ui_customer_dialog.h"

#include "../http_request/http_request.h"
#include "../xml_response_handler/xml_response_handler.h"
#include "../console/console.h"

class CustomerState;

class CustomerDialog : public QDialog
{
    Q_OBJECT

public:
    CustomerDialog(QNetworkAccessManager *manager, QUrl *url,
    		bool enableCancelButton, QWidget *parent = 0, Qt::WindowFlags f = 0);
    ~CustomerDialog();
    void setState(CustomerState *state);
    CustomerState* notFetchedState();
    CustomerState* fetchedState();
    QUrl url();
    Console* console();
    HttpRequest* httpRequest();
    XmlResponseHandler* xmlResponseHandler();
    void setCustomerKey(QString key);
    QString customerKey();
    LineEdit* nameLineEdit();

public slots:
	void fetchCustomer(QString nit);
	void setName(QString name);
	void save();

signals:
	void sessionStatusChanged(bool isActive);

private:
    Ui::CustomerDialogClass ui;

    CustomerState *m_State;
    CustomerState *m_NotFetchedState;
    CustomerState *m_FetchedState;

    QUrl *m_ServerUrl;
	Console *m_Console;
	HttpRequest *m_Request;
	XmlResponseHandler *m_Handler;
	QString m_Key;

	void setConsole();
};

#endif // CUSTOMER_DIALOG_H
