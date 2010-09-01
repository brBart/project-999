/*
 * deposit_section.h
 *
 *  Created on: 27/08/2010
 *      Author: pc
 */

#ifndef DEPOSIT_SECTION_H_
#define DEPOSIT_SECTION_H_

#include "document_section.h"

class DepositSection: public DocumentSection
{
	Q_OBJECT

public:
	DepositSection(QNetworkCookieJar *jar, QWebPluginFactory *factory,
			QUrl *serverUrl, QString cashRegisterKey, QWidget *parent = 0);
	virtual ~DepositSection() {};

protected:
	void setActions();
	void setMenu();
	void setActionsManager();
	void installRecordsetSearcher();
	void updateActions();

private:
	QString navigateValues();
};

#endif /* DEPOSIT_SECTION_H_ */
