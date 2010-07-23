/*
 * console.cpp
 *
 *  Created on: 27/04/2010
 *      Author: pc
 */

#include "console.h"

/**
 * @class Console
 * Use to display messages to the user.
 */

/**
 * Sets the console QWebFrame for displaying messages.
 */
void Console::setFrame(QWebFrame *frame)
{
	m_Div = frame->findFirstElement("#console");
}

/**
 * Displays a validation failure in the console.
 */
void Console::displayFailure(QString msg, QString elementId)
{
	QString newP = "<p id=\"failed-" + elementId + "\" class=\"failure\">"
			+ msg + "</p>";

	showElementIndicator(elementId);

	displayMessage(newP);
}

/**
 * Removes a validation failure from the console.
 */
void Console::cleanFailure(QString elementId)
{
	QWebElement elementP = m_Div.findFirst("#failed-" + elementId);

	// If there was a message.
	if (!elementP.isNull()) {
		elementP.removeFromDocument();
		m_Div.evaluateJavaScript("this.scrollTop = this.scrollHeight;");
		hideElementIndicator(elementId);
	}
}

/**
 * Displays an error message on the div.
 */
void Console::displayError(QString msg)
{
	QWebElement elementP = m_Div.findFirst("#error");
	// If there was a message.
	if (!elementP.isNull())
		elementP.removeFromDocument();

	QString newP = "<p id=\"error\" class=\"error\">" + msg + "</p>";
	displayMessage(newP);
}

/**
 * Cleans the console and hides all the element indicators.
 */
void Console::reset()
{
	QWebElementCollection collection = m_Div.findAll("p");

	foreach (QWebElement element, collection) {
		QString id = element.attribute("id");
		if (id != "error" && id != "info") {
			cleanFailure(id.mid(id.indexOf("-") + 1));
		} else {
			element.removeFromDocument();
		}
	}
}

/**
 * Displays the message on the div.
 */
void Console::displayMessage(QString msg)
{
	m_Div.appendInside(msg);
	m_Div.evaluateJavaScript("this.scrollTop = this.scrollHeight;");
}
