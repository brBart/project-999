#ifndef SEARCH_DEPOSIT_DIALOG_H
#define SEARCH_DEPOSIT_DIALOG_H

#include <QtGui/QDialog>
#include "ui_search_deposit_dialog.h"

#include <QNetworkCookieJar>
#include <QUrl>
#include "../console/console.h"
#include "../http_request/http_request.h"
#include "../xml_response_handler/xml_response_handler.h"

class SearchDepositDialog : public QDialog
{
    Q_OBJECT

public:
    enum SearchMode {ById, BySlipNumber};
    SearchDepositDialog(QNetworkCookieJar *jar, QUrl *url, QWidget *parent = 0,
    		Qt::WindowFlags f = 0);
    ~SearchDepositDialog();
    void init();
    SearchMode searchMode();
    QString depositId();
    QString bankId();
    QString slipNumber();

public slots:
	void setSearchMode(int button);

signals:
	void sessionStatusChanged(bool isActive);

private:
    Ui::SearchDepositDialogClass ui;
    QUrl *m_ServerUrl;
	Console *m_Console;
	HttpRequest *m_Request;
	XmlResponseHandler *m_Handler;
	SearchMode m_SearchMode;
};

#endif // SEARCH_DEPOSIT_DIALOG_H
