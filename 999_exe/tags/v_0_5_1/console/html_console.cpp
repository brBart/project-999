/*
 * html_console.cpp
 *
 *  Created on: 24/05/2010
 *      Author: pc
 */

#include "html_console.h"
/**
 * @class HtmlConsole
 * Use to display messages to the user using a html page.
 */

/**
 * Sets the main frame and then calls its parent's setFrame method.
 */
void HtmlConsole::setFrame(QWebFrame *frame)
{
	m_Frame = frame;
	Console::setFrame(frame);
}

/**
 * Changes the element's style class from "failed" to "hidden".
 */
void HtmlConsole::hideElementIndicator(QString elementId)
{
	QWebElement element = m_Frame->findFirstElement("#" + elementId + "-failed");
	element.removeClass("failed");
	element.addClass("hidden");
}

/**
 * Changes the element's style class form "hidden" to "failed".
 */
void HtmlConsole::showElementIndicator(QString elementId)
{
	QWebElement element = m_Frame->findFirstElement("#" + elementId + "-failed");
	element.removeClass("hidden");
	element.addClass("failed");
}
