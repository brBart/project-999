/*
 * main_section.cpp
 *
 *  Created on: 30/04/2010
 *      Author: pc
 */

#include "main_section.h"

#include <QWebFrame>

/**
 * @class MainSection
 * Displays the main menu or in case there is no session, the login form.
 */

/**
 *	Constructs the MainSection and contacts the server.
 */
MainSection::MainSection(QNetworkAccessManager *manager,
		QWebPluginFactory *factory, QUrl *serverUrl, QWidget *parent)
		: Section(manager, factory, serverUrl, parent)
{
	ui.webView->load(*m_ServerUrl);

	connect(ui.webView, SIGNAL(loadFinished(bool)), this,
				SLOT(loadFinished(bool)));
}

/**
 * Method overriding for obtaining the working day object key from the server.
 */
void MainSection::loadFinished(bool ok)
{
	if (ok) {
		QWebFrame *frame = ui.webView->page()->mainFrame();
		QString wdayKey = frame->evaluateJavaScript("wdayKey").toString();

		emit workingDayKeyReceived(wdayKey);
	}

	Section::loadFinished(ok);
}
