#ifndef CONSULT_PRODUCT_DIALOG_H
#define CONSULT_PRODUCT_DIALOG_H

#include <QtGui/QDialog>
#include "ui_consult_product_dialog.h"

#include <QNetworkCookieJar>
#include <QUrl>
#include "../console/console.h"
#include "../search_product/search_product_model.h"
#include "../http_request/http_request.h"
#include "../xml_response_handler/xml_response_handler.h"

class ConsultProductDialog : public QDialog
{
    Q_OBJECT

public:
    ConsultProductDialog(QNetworkCookieJar *jar, QUrl *url, SearchProductModel *model,
    		QWidget *parent = 0, Qt::WindowFlags f = 0);
    ~ConsultProductDialog();

public slots:
	void search(int id);

signals:
	void sessionStatusChanged(bool isActive);

private:
    Ui::ConsultProductDialogClass ui;
    Console *m_Console;
    QNetworkCookieJar *m_Jar;
    QUrl *m_ServerUrl;

    void fetchProduct(QString value, bool idBarCode = true);
};

#endif // CONSULT_PRODUCT_DIALOG_H
