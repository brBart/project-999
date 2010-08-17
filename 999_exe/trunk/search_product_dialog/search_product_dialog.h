#ifndef SEARCH_PRODUCT_DIALOG_H
#define SEARCH_PRODUCT_DIALOG_H

#include <QtGui/QDialog>
#include "ui_search_product_dialog.h"

#include <QNetworkCookieJar>
#include <QUrl>
#include <QCompleter>
#include <QQueue>
#include <QStandardItemModel>
#include <QTimer>
#include "../console/console.h"
#include "../http_request/http_request.h"
#include "../xml_response_handler/xml_response_handler.h"

class SearchProductDialog : public QDialog
{
    Q_OBJECT

public:
    SearchProductDialog(QNetworkCookieJar *jar, QUrl *url, QWidget *parent = 0,
    		Qt::WindowFlags f = 0);
    ~SearchProductDialog();

public slots:
	void checkForChanges();
	void fetchProducts();
	void updateProductModel(QString content);

signals:
	void sessionStatusChanged(bool isActive);

private:
    Ui::SearchProductDialogClass ui;
    QUrl *m_ServerUrl;
	Console *m_Console;
	HttpRequest *m_Request;
	XmlResponseHandler *m_Handler;

	QTimer m_CheckerTimer;
	QTimer m_SenderTimer;
	QQueue<QString> m_NamesQueue;
	QString m_Name;

	QCompleter *m_Completer;
	QStandardItemModel m_Model;
};

#endif // SEARCH_PRODUCT_DIALOG_H
