/*
 * working_day_section.h
 *
 *  Created on: 22/09/2010
 *      Author: pc
 */

#ifndef WORKING_DAY_SECTION_H_
#define WORKING_DAY_SECTION_H_

#include "object_section.h"

class WorkingDaySection: public ObjectSection
{
	Q_OBJECT

public:
	WorkingDaySection(QNetworkCookieJar *jar, QWebPluginFactory *factory,
			QUrl *serverUrl, QWidget *parent = 0);
	virtual ~WorkingDaySection() {};

protected:
	QUrl closeObjectUrl();
	QUrl reportUrl(bool isPreliminary);
	QUrl formUrl();
};

#endif /* WORKING_DAY_SECTION_H_ */
