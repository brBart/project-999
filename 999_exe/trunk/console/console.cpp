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
 * Displays the message on the div.
 */
void Console::displayMessage(QString msg)
{
	m_Div.appendInside(msg);
}

void Console::reset()
{
	m_Div.setInnerXml("");
}
