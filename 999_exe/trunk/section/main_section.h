/*
 * main_section.h
 *
 *  Created on: 30/04/2010
 *      Author: pc
 */

#ifndef MAIN_SECTION_H_
#define MAIN_SECTION_H_

#include "section.h"

class MainSection: public Section
{
public:
	MainSection(QNetworkAccessManager *manager, QUrl *serverUrl,
			QWidget *parent = 0);
	virtual ~MainSection() {};
};

#endif /* MAIN_SECTION_H_ */
