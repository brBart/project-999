/*
 * search_product_line_edit.h
 *
 *  Created on: 17/08/2010
 *      Author: pc
 */

#ifndef SEARCH_PRODUCT_LINE_EDIT_H_
#define SEARCH_PRODUCT_LINE_EDIT_H_

#include <QLineEdit>
#include <QQueue>
#include <QTimer>
#include <QFocusEvent>
#include <QStringList>
#include "../http_request/http_request.h"
#include "../xml_response_handler/xml_response_handler.h"
#include "../console/console.h"
#include "../search_product/search_product_model.h"

class SearchProductLineEdit : public QLineEdit
{
	Q_OBJECT

public:
	SearchProductLineEdit(QWidget *parent = 0);
	virtual ~SearchProductLineEdit() {};
	void init(QNetworkCookieJar *jar, QUrl *url, Console *console,
			SearchProductModel *model);
	QString barCode();

public slots:
	void checkForChanges();
	void fetchProducts();
	void updateProductModel(QString content);
	void itemChose(const QModelIndex &index);

signals:
	void sessionStatusChanged(bool isActive);
	void activated();

protected:
	void focusInEvent(QFocusEvent *e);
	void focusOutEvent(QFocusEvent *e);

private:
	QUrl *m_ServerUrl;
	Console *m_Console;
	HttpRequest *m_Request;
	XmlResponseHandler *m_Handler;

	QTimer m_CheckerTimer;
	QTimer m_SenderTimer;
	QQueue<QString> m_NamesQueue;
	QString m_BarCode;
	QStringList *m_Keywords;

	SearchProductModel *m_Model;
};

#endif /* SEARCH_PRODUCT_LINE_EDIT_H_ */
