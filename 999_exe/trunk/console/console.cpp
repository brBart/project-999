/*
 * console.cpp
 *
 *  Created on: 27/04/2010
 *      Author: pc
 */

#include "console.h"

#include <QWebElement>

/**
 * @class Console
 * Use to display messages to the user.
 */

/**
 * Constructs a Console using a QWebFrame.
 */
Console::Console(QWebFrame *frame) : m_Frame(frame)
{
	*m_Div = m_Frame->findFirstElement("#console");
}

/**
 * Displays an error message on the div.
 */
void Console::displayError(QString msg)
{
	QWebElement elementP = m_Div->findFirst("#error");
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
	m_Div->appendInside(msg);
	m_Div->evaluateJavaScript("scrollTop = scrollHeight");
}
