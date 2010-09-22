/*
 * cash_register_section.h
 *
 *  Created on: 18/09/2010
 *      Author: pc
 */

#ifndef CASH_REGISTER_SECTION_H_
#define CASH_REGISTER_SECTION_H_

#include "object_section.h"

class CashRegisterSection: public ObjectSection
{
	Q_OBJECT

public:
	CashRegisterSection(QNetworkCookieJar *jar, QWebPluginFactory *factory,
			QUrl *serverUrl, QString cashRegisterKey, QWidget *parent = 0);
	virtual ~CashRegisterSection() {};

protected:
	QUrl closeObjectUrl();
	QUrl reportUrl(bool isPreliminary);
	QUrl formUrl();

private:
	QString m_CashRegisterKey;
};

#endif /* CASH_REGISTER_SECTION_H_ */
