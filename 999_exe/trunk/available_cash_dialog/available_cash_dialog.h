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
    AvailableCashDialog(QNetworkCookieJar *jar, QUrl *url, QString cashRegisterKey,
    		QString depositKey, QWidget *parent = 0, Qt::WindowFlags f = 0);
    ~AvailableCashDialog();
    void init();

public slots:
	void setCashReceiptId(const QString id);
	void selectRadioButton(QTreeWidgetItem *item, int column);
	void addCashDeposit();

signals:
	void sessionStatusChanged(bool isActive);

private:
    Ui::AvailableCashDialogClass ui;
    QUrl *m_ServerUrl;
	Console *m_Console;
	HttpRequest *m_Request;
	XmlResponseHandler *m_Handler;
	QString m_CashRegisterKey;
	QString m_DepositKey;
	QString m_CashReceiptId;

	void setConsole();
	void populateList(QList<QMap<QString, QString>*> list);
};

#endif // AVAILABLE_CASH_DIALOG_H
