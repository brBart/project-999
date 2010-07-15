#ifndef VOUCHER_DIALOG_H
#define VOUCHER_DIALOG_H

#include <QtGui/QDialog>
#include "ui_voucher_dialog.h"

#include "../console/console.h"
#include "../http_request/http_request.h"
#include "../xml_response_handler/xml_response_handler.h"

class VoucherDialog : public QDialog
{
    Q_OBJECT

public:
    VoucherDialog(QNetworkCookieJar *jar, QUrl *url, QWidget *parent = 0,
    		Qt::WindowFlags f = 0);
    ~VoucherDialog();
    void init();

/*public slots:
	void addVoucher();*/

signals:
	void sessionStatusChanged(bool isActive);

private:
    Ui::VoucherDialogClass ui;
    QUrl *m_ServerUrl;
    Console *m_Console;
    HttpRequest *m_Request;
    XmlResponseHandler *m_Handler;

    void setConsole();
    void fetchTypes();
    void fetchBrands();
};

#endif // VOUCHER_DIALOG_H
