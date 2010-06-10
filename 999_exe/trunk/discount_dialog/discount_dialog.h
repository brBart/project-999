#ifndef DISCOUNT_DIALOG_H
#define DISCOUNT_DIALOG_H

#include <QtGui/QDialog>
#include "ui_discount_dialog.h"

#include <QNetworkCookieJar>
#include "../console/console.h"
#include "../http_request/http_request.h"
#include "../xml_response_handler/xml_response_handler.h"

class DiscountDialog : public QDialog
{
    Q_OBJECT

public:
    DiscountDialog(QNetworkCookieJar *jar, QUrl *url, QString discountKey,
    		QWidget *parent = 0, Qt::WindowFlags f = 0);
    ~DiscountDialog();

public slots:
	void setPercentage(QString value);
	void percentageSetted(QString content);
	void okClicked();

private:
    Ui::DiscountDialogClass ui;
    QUrl *m_ServerUrl;
	Console *m_Console;
	HttpRequest *m_Request;
	XmlResponseHandler *m_Handler;
    QString m_DiscountKey;
    bool m_IsPercentageSet;

    void setConsole();
};

#endif // DISCOUNT_DIALOG_H
