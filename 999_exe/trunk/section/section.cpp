#include "section.h"

#include <QNetworkCookieJar>
#include <QWebFrame>
#include <QFile>
#include <QTextStream>

/**
 * @class Section
 * Base class for the sections on the system.
 */

/**
 * Constructs a Section with a QNetworkAccessManager and a parent.
 */
Section::Section(QNetworkCookieJar *jar, QWebPluginFactory *factory,
		QUrl *serverUrl, QWidget *parent) : QWidget(parent), m_ServerUrl(serverUrl)
{
	ui.setupUi(this);

	ui.webView->page()->networkAccessManager()->setCookieJar(jar);
	jar->setParent(0);
	ui.webView->page()->setPluginFactory(factory);
	ui.webView->setContextMenuPolicy(Qt::PreventContextMenu);

	connect(ui.webView, SIGNAL(loadFinished(bool)), this,
			SLOT(loadFinished(bool)));
}

/**
 * Slot use to detect if the page loads successfully. If not the QWebView displays
 * an error message.
 */
void Section::loadFinished(bool ok)
{
	QWebFrame *frame = ui.webView->page()->mainFrame();

	if (ok) {
		bool sessionStatus = frame->evaluateJavaScript("isSessionActive").toBool();

		emit sessionStatusChanged(sessionStatus);
	} else {
		QFile file(":/resources/not_found.html");
		file.open(QIODevice::ReadOnly);
		QTextStream stream(&file);

		ui.webView->setHtml(stream.readAll());

		emit sessionStatusChanged(false);

		file.close();
	}

	frame->addToJavaScriptWindowObject("mainWindow", parent());
}
