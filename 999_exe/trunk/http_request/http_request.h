/*
 * httprequest.h
 *
 *  Created on: 11/03/2010
 *      Author: pc
 */

#ifndef HTTPREQUEST_H_
#define HTTPREQUEST_H_

#include <QNetworkCookieJar>
#include <QNetworkAccessManager>
#include <QUrl>
#include <QNetworkReply>

class HttpRequest : public QObject
{
	Q_OBJECT

public:
	HttpRequest(QNetworkCookieJar *jar, QObject *parent = 0);
	virtual ~HttpRequest() {};
	QString get(QUrl url, bool isAsync = false);
	QNetworkCookieJar* cookieJar();

private slots:
	void loadFinished(QNetworkReply *reply);

signals:
	void finished(QString content);

private:
	QNetworkAccessManager m_Manager;
};

#endif /* HTTPREQUEST_H_ */
