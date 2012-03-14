/*
 * working_day_section.cpp
 *
 *  Created on: 22/09/2010
 *      Author: pc
 */

#include "working_day_section.h"

WorkingDaySection::WorkingDaySection(QNetworkCookieJar *jar,
		QWebPluginFactory *factory, QUrl *serverUrl, QWidget *parent)
		: ObjectSection(jar, factory, serverUrl, parent)
{

}

/**
 * Returns the url to use for closing the object on the server.
 */
QUrl WorkingDaySection::closeObjectUrl()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "close_working_day");
	url.addQueryItem("type", "xml");

	return url;
}

/**
 * Returns the url to use for fetching the report.
 */
QUrl WorkingDaySection::reportUrl(bool isPreliminary)
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "print_general_sales_report");
	url.addQueryItem("is_preliminary", isPreliminary ? "1" : "0");

	return url;
}

/**
 * Returns the url to use for fetching the form.
 */
QUrl WorkingDaySection::formUrl()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "show_working_day_form");

	return url;
}
