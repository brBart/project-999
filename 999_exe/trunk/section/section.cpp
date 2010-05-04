#include "section.h"

#include <QNetworkAccessManager>
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
Section::Section(QNetworkAccessManager *manager, QWebPluginFactory *factory,
		QUrl *serverUrl, QWidget *parent) : QWidget(parent), m_ServerUrl(serverUrl)
{
	ui.setupUi(this);

	ui.webView->page()->setNetworkAccessManager(manager);
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
	if (ok) {
		QWebFrame *frame = ui.webView->page()->mainFrame();
		bool sessionStatus = frame->evaluateJavaScript("isSessionActive").toBool();

		emit sessionStatusChanged(sessionStatus);

		frame->addToJavaScriptWindowObject("mainWindow", parent());
	} else {
		QFile file(":/resources/html_error/not_found.html");
		file.open(QIODevice::ReadOnly);
		QTextStream stream(&file);

		ui.webView->setHtml(stream.readAll());

		emit sessionStatusChanged(false);

		file.close();
	}
}
