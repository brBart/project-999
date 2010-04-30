/*
 * httprequest.h
 *
 *  Created on: 11/03/2010
 *      Author: pc
 */

#ifndef HTTPREQUEST_H_
#define HTTPREQUEST_H_

#include <QObject>
#include <QNetworkAccessManager>
#include <QUrl>
#include <QNetworkReply>

class HttpRequest : public QObject
{
	Q_OBJECT

public:
	enum ConnectionType {Sync, Async};
	HttpRequest(QNetworkAccessManager *manager, QObject *parent = 0);
	QString get(QUrl url, ConnectionType type = Sync);
	virtual ~HttpRequest() {};

private slots:
	void loadFinished(QNetworkReply *reply);

signals:
	void finished(QString content);

private:
	QNetworkAccessManager *m_Manager;
};

#endif /* HTTPREQUEST_H_ */
