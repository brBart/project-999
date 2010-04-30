/*
 * main_section.cpp
 *
 *  Created on: 30/04/2010
 *      Author: pc
 */

#include "main_section.h"

/**
 * @class MainSection
 * Displays the main menu or in case there is no session, the login form.
 */

/**
 *	Constructs the MainSection and contacts the server.
 */
MainSection::MainSection(QNetworkAccessManager *manager, QUrl *serverUrl,
		QWidget *parent) : Section(manager, serverUrl, parent)
{
	ui.webView->load(*m_ServerUrl);
}
