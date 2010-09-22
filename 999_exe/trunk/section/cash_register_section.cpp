/*
 * cash_register_section.cpp
 *
 *  Created on: 18/09/2010
 *      Author: pc
 */

#include "cash_register_section.h"

/**
 * @class CashRegisterSection
 * Section for controlling the cash register actions.
 */

/**
 * Constructs the section.
 */
CashRegisterSection::CashRegisterSection(QNetworkCookieJar *jar,
		QWebPluginFactory *factory, QUrl *serverUrl, QString cashRegisterKey,
		QWidget *parent) : ObjectSection(jar, factory, serverUrl, parent),
		m_CashRegisterKey(cashRegisterKey)
{

}

/**
 * Returns the url to use for closing the object on the server.
 */
QUrl CashRegisterSection::closeObjectUrl()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "close_cash_register");
	url.addQueryItem("key", m_CashRegisterKey);
	url.addQueryItem("type", "xml");

	return url;
}

/**
 * Returns the url to use for fetching the report.
 */
QUrl CashRegisterSection::reportUrl(bool isPreliminary)
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("cmd", "print_sales_report");
	url.addQueryItem("register_key", m_CashRegisterKey);
	url.addQueryItem("is_preliminary", isPreliminary ? "1" : "0");

	return url;
}

/**
 * Returns the url to use for fetching the form.
 */
QUrl CashRegisterSection::formUrl()
{
	QUrl url(*m_ServerUrl);
	url.addQueryItem("register_key", m_CashRegisterKey);
	url.addQueryItem("cmd", "show_cash_register_form");

	return url;
}
