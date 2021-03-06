/*
 * httprequest.cpp
 *
 *  Created on: 11/03/2010
 *      Author: pc
 */

#include "http_request.h"

#include <QEventLoop>

/**
 * @class HttpRequest
 * Handles synchronous or asynchronous communication with the server.
 */

/**
 * Constructs a HtttpRequest with the QNetworkAccessManager and parent.
 */
HttpRequest::HttpRequest(QNetworkCookieJar *jar, QObject *parent)
		: QObject(parent)
{
	m_Manager.setCookieJar(jar);
	jar->setParent(0);
	m_IsBusy = false;
}

/**
 * Gets the information from the server.
 * If isAsync is true the finished signal is emitted sending the data received.
 */
QString HttpRequest::get(QUrl url, bool isAsync)
{
	if (!isAsync) {
		QEventLoop loop;

		QNetworkReply *reply = m_Manager.get(QNetworkRequest(url));

		connect(reply, SIGNAL(finished()), &loop, SLOT(quit()));

		loop.exec();
		return QString::fromUtf8(reply->readAll());
	}

	connect(&m_Manager, SIGNAL(finished(QNetworkReply*)), this,
			SLOT(loadFinished(QNetworkReply*)));

	m_Manager.get(QNetworkRequest(url));
	m_IsBusy = true;

	return NULL;
}

/**
 * Returns the CookieJar object use by this request.
 */
QNetworkCookieJar* HttpRequest::cookieJar()
{
	return m_Manager.cookieJar();
}

/**
 * Returns true if the request object is busy waiting a response.
 */
bool HttpRequest::isBusy()
{
	return m_IsBusy;
}

/**
 * Handles the response in case the communication was made asynchronously.
 * Emits the finished signal.
 */
void HttpRequest::loadFinished(QNetworkReply *reply)
{
	emit finished(QString::fromUtf8(reply->readAll()));

	m_IsBusy = false;

	m_Manager.disconnect(this);
}
